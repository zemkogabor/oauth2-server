<?php

declare(strict_types = 1);

namespace App\Console;

use App\Entity\UserEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateUserCommand extends Command
{
    protected static $defaultName = 'user:create';
    protected static $defaultDescription = 'Creates a new user.';

    public function __construct(protected EntityManager $entityManager, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'User email');
        $this->addArgument('name', InputArgument::REQUIRED, 'User name');
        $this->addArgument('password', InputArgument::REQUIRED, 'Client password');
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $name = $input->getArgument('name');
        $password = $input->getArgument('password');

        $client = new UserEntity();
        $client->setEmail($email);
        $client->setName($name);
        $client->setPassword($password);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $output->writeln('<info>User created</info>');
        $output->writeln('Email: ' . $client->getEmail());
        $output->writeln('Name: ' . $client->getName());
        $output->writeln('Password: ***');

        return Command::SUCCESS;
    }
}
