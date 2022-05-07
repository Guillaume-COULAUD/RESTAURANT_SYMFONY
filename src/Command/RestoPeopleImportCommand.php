<?php

namespace App\Command;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\RhService;

class RestoPeopleImportCommand extends Command
{
    protected static $defaultName = 'resto:people:import';
    protected static $defaultDescription = 'Add a short description for your command';

    private $rhService;
    private $userRepository;
    private $em;
    /*
    public function __construct(RhService $rhService, UserRepository $userRepository, EntityManagerInterface $em,string $name = null)
    {
        $this->rhService =$rhService;
        $this->userRepository = $userRepository;
        $this->em = $em;
        parent::__construct($name);
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $peoples = $this->rhService->getPeople();

        foreach ($peoples as $people) {
            if ($user = $this->userRepository->findOneBy(['username' => $people['id']])) {
                $user->setFirstname($people['FirstName'])
                    ->setLastName($people['Lastname'])
                    ->setEmail($people['Email'])
                    ->setJobtitle($people['JobTitle']);

                $io->success(sprintf('%s updated', $user->getUsername()));
            } else {
                $user = (new User())
                    ->setUsername($people['id'])
                    ->setFirstname($people['FirstName'])
                    ->setLastName($people['Lastname'])
                    ->setEmail($people['Email'])
                    ->setJobtitle($people['JobTitle'])
                    ->setEnabled(true)
                    ->setCreatedAt(new \DateTime());
                $io->success(sprintf('%s created', $user->getUsername()));
            }
            $this->em->persist($user);
            $this->em->flush();
        }

        $io->success('Reussite');

        return Command::SUCCESS;
    }
    */
}