<?php

    class AuthService 
    {
        private UtilisateurRepository $userRepo;

        public function __construct() 
        {
            $this->userRepo = new UtilisateurRepository();
        }

        public function login(string $email, string $motDePasse): Utilisateur 
        {
            $user = $this->userRepo->findByEmail($email);
            
            if(!$user) {
                throw new Exception('Invalide credentials');
            }

            if(!$user->isActif()) {
                throw new Exception('Account Disable');
            }

            if(!password_verify($motDePasse, $user->getMotDePass())) {
                throw new Exception('Invalid credentials');
            }

            return $user;
        }

        public function singup(string $nom, string $email, string $motDePasse, int $roleId): void {
            $existing = $this->userRepo->findByEmail($email);

            if($existing) {
                throw new Exception('Email already exists');
            }

            $this->userRepo->create($nom, $email, $motDePasse, $roleId);
        }
    }

?>