<?php

declare(strict_types = 1);

namespace App\Console;

use App\Entity\ClientEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateClientCommand extends Command
{

    protected static $defaultName = 'client:create';
    protected static $defaultDescription = 'Creates a new client.';

    public function __construct(protected EntityManager $entityManager, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Client name');
        $this->addArgument('secret', InputArgument::REQUIRED, 'Client secret');
        $this->addArgument('redirect-uri', InputArgument::REQUIRED, 'Redirect URI');
        $this->addOption(
            'confidential',
            'c',
            InputOption::VALUE_NONE,
            'Identify a client as confidential or public',
        );
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $secret = $input->getArgument('secret');
        $redirectUri = $input->getArgument('redirect-uri');
        $isConfidential = $input->getOption('confidential');

        $client = new ClientEntity();
        $client->setName($name);
        $client->setSecret($secret);
        $client->setRedirectUri($redirectUri);
        $client->setIsconfidential($isConfidential);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $output->writeln('<info>Client created</info>');
        $output->writeln('Name: ' . $client->getName());
        $output->writeln('Client ID: ' . $client->getIdentifier());
        $output->writeln('Secret: ' . $client->getSecret());
        $output->writeln('Redirect Uri: ' . $client->getRedirectUri());
        $output->writeln('Confidential: ' . ($client->isConfidential() ? 'Yes' : 'No'));

        return Command::SUCCESS;
    }
}
