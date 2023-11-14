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
        $this->addArgument('redirect-uri', InputArgument::REQUIRED, 'Redirect URI');
        $this->addArgument('secret', InputArgument::OPTIONAL, 'Client secret');
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
        $redirectUri = $input->getArgument('redirect-uri');

        if ($input->hasArgument('secret')) {
            $secret = $input->getArgument('secret');
        } else {
            $secret = null;
        }

        $isConfidential = $input->getOption('confidential');

        $client = new ClientEntity();
        $client->setName($name);
        $client->setRedirectUri($redirectUri);
        if ($secret !== null) {
            $client->setSecret($secret);
        }
        $client->setIsconfidential($isConfidential);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $output->writeln('<info>Client created</info>');
        $output->writeln('Name: ' . $client->getName());
        $output->writeln('Client ID: ' . $client->getIdentifier());
        $output->writeln('Redirect Uri: ' . $client->getRedirectUri());
        if ($secret !== null) {
            $output->writeln('Secret: ' . $client->getSecret());
        }
        $output->writeln('Confidential: ' . ($client->isConfidential() ? 'Yes' : 'No'));

        return Command::SUCCESS;
    }
}
