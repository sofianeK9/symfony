<?php

// Ce fichier sert à créer et à insérer des données fictives dans la base de données de votre application Symfony

// ce fichier appartient à App dans le dossier dataFixtures
namespace App\DataFixtures;
// permet d'importer la classe User qui se trouve dans le dossier Entity dans lequel on créér des entités
use App\Entity\User;
// on importe la classe Fixtures du bundle doctrinefixtures pour créér des fixtures personnalisées
use Doctrine\Bundle\FixturesBundle\Fixture;
// Cette interface est utilisée pour regrouper les fixtures afin qu'elles puissent être chargées en groupe.
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
// on importe la classe objectmanager du package doctrine\common\persistence cette classe permet d'interagir avec l'ORM pour gérer les objets et les operations de BDD
use Doctrine\Persistence\ObjectManager;
// on importe la classe factory pour générer des données fictives comme des noms, adresses,dates etc...
use Faker\Factory as FakerFactory;
// on importe pour hasher le MDP
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



// Cette partie du code définit la classe AppFixtures, qui étend à la fois la classe Fixture et implémente l'interface FixtureGroupInterface. 
// Cette interface est utilisée pour regrouper les fixtures afin qu'elles puissent être chargées en groupe.
class AppFixtures extends Fixture implements FixtureGroupInterface
{
    // propriétés privées qui seront utilisées au sein de la classe 
    // faker permet de créér des valeurs fictives
    private $faker;
    // hasher permet de hasher le MDP
    private $hasher;
    // manager s'occuper de la gestion des objets et de leur intéraction avec la BDD    
    private $manager;

    // le constructeur prend en argument UserPasswordHasherInterface $hasher ce qui signifie qu'une instance de ce dernier doit étre passée
    // lors de la création d'une instance AppFixtures.

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        // une instance est crée pour la langue et le haschage du MDP
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        // La méthode statique getGroups de la classe retourne un tableau contenant les noms des groupes auxquels cette fixture appartient.
        //  Dans cet exemple, la fixture appartient aux groupes 'prod' et 'test'.
        return ['prod', 'test'];
    }

    public function load(ObjectManager $manager): void
    {

        // La méthode load est appelée lors du chargement des fixtures, Elle prend un objet ObjectManager en argument et stocke cet objet dans la propriété $manager.
        // La méthode appelle ensuite la méthode loadAdmins() pour charger les administrateurs fictifs.
        $this->manager = $manager;
        $this->loadAdmins();
    }

    public function loadAdmins(): void
    {
        // données statiques
        // Un tableau $datas contenant des données statiques est défini. Dans cet exemple, il y a un ensemble d'informations pour un administrateur.
        $datas = [
            [
                'email' => 'admin@exemple.com',
                'password' => '123',
                'roles' => ['ROLE_ADMIN'],
            ],
        ];
        // Une boucle foreach parcourt chaque ensemble de données dans $datas, crée un nouvel objet User, définit son email,
        //  hash le mot de passe à l'aide de l'instance de UserPasswordHasherInterface et définit le rôle.
        foreach ($datas as $data) {
            // créér un nouvel objet user vide
            $user = new User();
            // email défini en utilisant l'attribut email du tableau $datas
            $user->setEmail($data['email']);
            // ici on hasche le MDP et est attribué à l'objet user
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            // on attribue un role à l'objet user
            $user->setRoles(['roles']);

            // L'objet User est persisté en utilisant la méthode $manager->persist($user) et sera inséré dans la BDD
            $this->manager->persist($user);
        }
        // Une fois la boucle terminée, la méthode $manager->flush() est appelée pour enregistrer les données dans la base de données.
        $this->manager->flush();
    }
}


// En résumé, ce code définit une classe de fixtures Symfony qui crée des utilisateurs fictifs avec des rôles et des mots de passe hashés. 
// Les données sont générées à l'aide de la bibliothèque Faker et sont persistées dans la base de données.

// Dans le code que vous avez fourni, la classe AppFixtures définit un ensemble d'utilisateurs 
// fictifs avec des rôles et des mots de passe hashés.
// Ces données fictives sont utilisées pour créer des administrateurs factices dans la base de données. 
// Les fixtures permettent ainsi de rapidement avoir des utilisateurs en place pour des tests ou des démonstrations,
// sans avoir à entrer manuellement ces données dans la base de données.