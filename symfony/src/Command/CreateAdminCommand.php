<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin', 
    description: 'Crée le premier utilisateur admin'
)]
class CreateAdminCommand extends Command
{
    

    public function __construct(private UserRepository $userRepository, private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            ->setDescription('Crée le premier utilisateur admin');;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $existing = $this->userRepository->findOneBy(['roles' => ['ROLE_ADMIN']]);
        if ($existing) {
            $output->writeln('Un admin existe déjà ✅');
            return Command::SUCCESS;
        }
        // create new admin user
        $adminUser = new User;
        $adminUser->setFirstname('admin');
        $adminUser->setLastname('Administrateur');
        $adminUser->setEmail('admin@example.com');
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, 'admin'));
        $adminUser->setRoles(['ROLE_ADMIN']);

        $this->userRepository->save($adminUser, true);

        $output->writeln('Premier admin créé ✅');

        return Command::SUCCESS;
    }
}
