<?php
// Ce fichier est un fichier de fixtures Symfony qui sert à générer et 
// insérer des données fictives dans la base de données de votre application

namespace App\DataFixtures;

use DateTime;
use App\Entity\Project;
use App\Entity\SchoolYear;
use App\Entity\Student;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\Messenger\Middleware\StackMiddleware;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadTags();
        $this->loadSchoolYears();
        $this->loadProjects();
        $this->loadStudents();
       
    }

    public function loadTags(): void
    {
        //données statiques
        $datas = [
            [
                'name' => 'HTML',
                'description' => null,
            ],
            [
                'name' => 'CSS',
                'description' => null,
            ],
            [
                'name' => 'JS',
                'description' => null,
            ],
        ];

        foreach ($datas as $data) {
            $tag = new Tag();
            $tag->setName($data['name']);
            $tag->setDescription($data['description']);

            $this->manager->persist($tag);
        }

        $this->manager->flush();

        // données dynamiques
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag();
            $words = random_int(1, 3);
            $tag->setName($this->faker->unique()->sentence($words));
            $words = random_int(8, 15);
            $tag->setDescription($this->faker->sentence($words));

            $this->manager->persist($tag);
        }
        $this->manager->flush();
    }

    public function loadSchoolYears(): void
    {
        //données statiques
        $datas = [
            [
                'name' => 'Alan Turing',
                'description' => null,
                'startDate' => new DateTime('2022-01-01'),
                'endDate' => new DateTime('2022-12-31'),
            ],
            [
                'name' => 'John van Neuman',
                'description' => null,
                'startDate' => new DateTime('2022-06-01'),
                'endDate' => new DateTime('2023-05-31'),
            ],
            [
                'name' => 'Brendan Eich',
                'description' => null,
                'startDate' => null,
                'endDate' => null,
            ],
        ];

        foreach ($datas as $data) {
            $schoolYear = new SchoolYear();
            $schoolYear->setName($data['name']);
            $schoolYear->setDescription($data['description']);
            $schoolYear->setStartDate($data['startDate']);
            $schoolYear->setEndDate($data['endDate']);

            $this->manager->persist($schoolYear);
        }

        $this->manager->flush();

        // données dynamiques

        for ($i = 0; $i < 5; $i++) {
            $schoolyear = new SchoolYear();
            $words = random_int(2, 4);
            $schoolyear->setName($this->faker->unique()->sentence($words));
            $words = random_int(2, 4);
            $schoolyear->setDescription($this->faker->optional($weight = 0.7)->sentence($words));

            $startDate = $this->faker->dateTimeBetween('-1 year', '-6 months');
            $schoolYear->setStartDate($startDate);

            $endDate = $this->faker->dateTimeBetween('-6 months', 'now');
            $schoolYear->setEndDate($endDate);

            $this->manager->persist($schoolYear);
        }
        $this->manager->flush();
    }




    public function loadStudents(): void
    {

        $repository = $this->manager->getRepository(SchoolYear::class);
        $schoolYears = $repository->findAll();

        $allanTuring = $repository->find(1);
        $johnVonNeuman = $repository->find(2);
        $brendanEich = $schoolYears[1];

        // recuperer la liste de tous les tags

        $repository = $this->manager->getRepository(Tag::class);
        $tags = $repository->findAll();

        $html = $repository->find(1);
        $css = $repository->find(2);
        $js = $repository->find(3);

        // recupérer la liste des projets
        $repository = $this->manager->getRepository(Project::class);
        $projects = $repository->findAll();

        $siteVitrine = $repository->find(1);
        $wordpress = $repository->find(2);
        $apiRest = $repository->find(3);

        //données statiques
        $datas = [
            [
                'email' => 'foo@exemple.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'firstname' => 'foo',
                'lastname' => 'example',
                'schoolYear' => $allanTuring,
                'projects' => [$siteVitrine],
                'tags' => [$html]
            ],
            [
                'email' => 'bar@exemple.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'firstname' => 'bar',
                'lastname' => 'example',
                'schoolYear' => $johnVonNeuman,
                'projects' => [$wordpress],
                'tags' => [$css],
            ],
            [
                'email' => 'baz@exemple.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'firstname' => 'baz',
                'lastname' => 'example',
                'schoolYear' => $brendanEich,
                'projects' => [$apiRest],
                'tags' => [$js],
            ],
        ];

        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['roles']);

            $this->manager->persist($user);


            $student = new Student();
            $student->setFirstName($data['firstname']);
            $student->setLastName($data['lastname']);
            $student->setSchoolYear($data['schoolYear']);
            $student->setUser($user);


            // recuperation du premier projet de la liste student
            $project = $data['projects'][0];
            $student->addProject($project);

            // recuperer aussi ici les tags 
            foreach($data['tags'] as $tag) {
                $student->addTag($tag);
            }
           

            $this->manager->persist($student);

        }

        $this->manager->flush();

        //données dynamiques
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail($this->faker->safeEmail());
            $password = $this->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $this->manager->persist($user);

            $student = new Student();
            $student->setFirstName($this->faker->firstName());
            $student->setLastName($this->faker->lastName());

            $schoolYear = $this->faker->randomElement($schoolYears);
            $student->setSchoolYear($schoolYear);


            // faire pareil mais avec les tags
            $project = $this->faker->randomElement($projects);
            $student->addProject($project);

            //  tag

            $count = random_int(1, 4);          
            $shortList = $this->faker->randomElement($tags, $count);
            foreach ($shortList as $tag) {
                $student->addTag($tag);
            }
           

            $student->setUser($user);


            $this->manager->persist($student);
        }

        $this->manager->flush();
    }

    public function loadProjects(): void
    {
        $repository = $this->manager->getRepository(Tag::class);
        $tags = $repository->findAll();

        // recuperation d'un tag à partir de son id, on recupere un objet de type tag

        $htmlTag = $repository->find(1);
        $cssTag = $repository->find(2);
        $jsTag = $repository->find(3);

        // elements de code à réutiliser dans vos boucles
        $html = $tags[0];
        $html->getName();

        $tags[0]->getNAME();

        $shortlist = $this->faker->randomElements($tags, 3);
        // données statiques

        $datas = [
            [
                'name' => 'Site vitrine',
                'description' => null,
                'clientName' =>  'Alice',
                'startDate' => new DateTime('2022-10-01'),
                'checkPointDate' => new DateTime('2022-11-01'),
                'deliveryDate' => new DateTime('2022-12-01'),
                // on associe un projet à des tags
                'tags' => [$htmlTag, $cssTag],
            ],
            [
                'name' => 'WordPress',
                'description' => null,
                'clientName' =>  'Bob',
                'startDate' => new DateTime('2022-02-01'),
                'checkPointDate' => new DateTime('2022-03-01'),
                'deliveryDate' => new DateTime('2022-04-01'),
                'tags' => [$jsTag, $cssTag],
            ],
            [
                'name' => 'API rest',
                'description' => null,
                'clientName' =>  'Charlie',
                'startDate' => new DateTime('2022-05-01'),
                'checkPointDate' => new DateTime('2022-06-01'),
                'deliveryDate' => new DateTime('2022-07-01'),
                'tags' => [$jsTag],
            ],

        ];

        foreach ($datas as $data) {

            $project = new Project();
            $project->setName($data['name']);
            $project->setDescription($data['description']);
            $project->setClientName($data['clientName']);
            $project->setStartDate($data['startDate']);
            $project->setCheckPointDate($data['checkPointDate']);
            $project->setDeliveryDate($data['deliveryDate']);

            foreach ($data['tags'] as $tag) {
                $project->addTag($tag);
            }

            $this->manager->persist($project);
        }
        $this->manager->flush();

        // données dynamiques

        for ($i = 0; $i < 30; $i++) {
            $project = new Project();

            $words = random_int(3, 5);
            $project->setName($this->faker->unique()->sentence($words));

            $words = random_int(5, 15);
            $project->setDescription($this->faker->optional(0.7)->sentence($words));

            $project->setClientName($this->faker->name());
            $startDate = $this->faker->dateTimeBetween('-12 months', '-10 months');
            $checkPointDate = $this->faker->dateTimeBetween('-10 months', '-8 months');
            $deliveryDate = $this->faker->dateTimeBetween('-8 months', '-6 months');

            // on tire au hasard un nombre de tag
            $tagsCount = random_int(1, 4);
            $shortList = $this->faker->randomElements($tags, $tagsCount);
            // on passe en revue chaque tag de la shortlist
            foreach ($shortList as $tag) {
                // on associe un tag avec le projet
                $project->addTag($tag);
            }



            $this->manager->persist($project);
        }

        $this->manager->flush();
    }
}
