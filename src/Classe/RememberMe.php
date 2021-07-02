<?php
    
namespace App\Classe;
    
    
use Doctrine\ORM\EntityManagerInterface;

class RememberMe
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    
    private function createRememberMeTokenTable(): void
    {
        $sqlQuery = "CREATE TABLE `rememberme_token` (
            `series`   char(88)     UNIQUE PRIMARY KEY NOT NULL,
            `value`    varchar(88)  NOT NULL,
            `lastUsed` datetime     NOT NULL,
            `class`    varchar(100) NOT NULL,
            `username` varchar(200) NOT NULL
        );";
        
        $this->entityManager->getConnection()->exec($sqlQuery);
    }
}