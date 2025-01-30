<?php

namespace App\Command;

use App\Component\User\UserManager;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'roles:add-to-user',
    description: "user'ga role qo'shish",
    aliases: ['r:add']
)]
class RolesAddToUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserManager $userManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $idQuestion = new Question("Id kiriting: ");
        $roleQuestion = new Question("Role kiriting: ");
        $questionHelper = $this->getHelper('question');

        $user = null;
        $role = '';

        while (!$user) {
            $id = $questionHelper->ask($input, $output, $idQuestion);

            $user = $this->userRepository->find($id);

            if (!$user) {
                $io->warning("Bunday user topilmadi!!!");
            }
        }

        while (!preg_match('/^ROLE_[A-Z]{4,}$/', $role)) {
            $role = $questionHelper->ask($input, $output, $roleQuestion);

            if (!preg_match('/^ROLE_[A-Z]{4,}$/', $role)) {
                $io->warning("No'g'ri role kiritmoqdasiz. Mos variant: ROLE_ABCD!");
            }
        }

        if (!in_array($role, $user->getRoles(), true)) {
            $roles = $user->getRoles();

            $roles[] = $role;

            $user->setRoles($roles);
            $this->userManager->save($user, true);

            $io->success("ROLE muvaffaqiyatli qo'shildi!");
            $io->block("Biz Uddaladik", "Donne", prefix: '  ');
        }

        return Command::SUCCESS;
    }
}
