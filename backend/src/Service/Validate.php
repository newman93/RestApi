<?php

namespace App\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validate {
    private $validator;
    private $entityManager;

    /**
     * Validate constructor
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator, ManagerRegistry $registry) {
        $this->validator =  $validator;
        $this->entityManager = $registry;
    }

    public function validateRequest($data) {
        $errors = $this->validator->validate($data);

        $errorsResponse = [];

        foreach ($errors as $error) {
            $errorsResponse[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage()        
            ];
        }

        if (count($errors)) {
            $response = [
                'code' => 1,
                'message' => 'validation errors',
                'error' => $errorsResponse,
                'result' => null
            ];

            return $response;
        } else {
            $response = [];
        
            return $response;
        }
    }
}