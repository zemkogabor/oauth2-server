<?php

declare(strict_types = 1);

namespace App\Console;

use App\Manager\AccessTokenManager;
use App\Manager\RefreshTokenManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ClearExpiredTokensCommand extends Command
{
    protected static $defaultName = 'clear-expired-tokens';
    protected static $defaultDescription = 'Clears all expired access and/or refresh tokens.';

    public function __construct(
        protected RefreshTokenManager $refreshTokenManager,
        protected AccessTokenManager $accessTokenManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'refresh-tokens',
            'r',
            InputOption::VALUE_NONE,
            'Clear expired refresh tokens.'
        );

        $this->addOption(
            'access-tokens',
            'a',
            InputOption::VALUE_NONE,
            'Clear expired access tokens.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('refresh-tokens')) {
            $this->clearExpiredRefreshTokens($io);
        }

        if ($input->getOption('access-tokens')) {
            $this->clearExpiredAccessTokens($io);
        }

        return Command::SUCCESS;
    }

    protected function clearExpiredRefreshTokens(SymfonyStyle $io): void
    {
        $numOfClearedRefreshTokens = $this->refreshTokenManager->clearExpired();
        $io->success(sprintf(
            'Cleared %d expired refresh token%s.',
            $numOfClearedRefreshTokens,
            $numOfClearedRefreshTokens === 1 ? '' : 's'
        ));
    }

    protected function clearExpiredAccessTokens(SymfonyStyle $io): void
    {
        $numOfClearedAccessTokens = $this->accessTokenManager->clearExpired();
        $io->success(sprintf(
            'Cleared %d expired access token%s.',
            $numOfClearedAccessTokens,
            $numOfClearedAccessTokens === 1 ? '' : 's'
        ));
    }
}
