<?php

declare(strict_types = 1);

namespace App\Console;

use App\Manager\AccessTokenManager;
use App\Manager\RefreshTokenManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ClearExpiredTokensCommand extends Command
{
    protected static $defaultName = 'clear-expired-tokens';
    protected static $defaultDescription = 'Clears all expired access and refresh tokens.';

    public function __construct(
        protected RefreshTokenManager $refreshTokenManager,
        protected AccessTokenManager $accessTokenManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->clearExpiredRefreshTokens($io);
        $this->clearExpiredAccessTokens($io);

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
