<?php
namespace charlymatloc\core\application\usecases;

use Exception;
use charlymatloc\core\application\ports\api\AuthzUtilisateurServiceInterface;

class AuthzUtilisateurService implements AuthzUtilisateurServiceInterface {
    public int $OPERATION_READ = 1;
    public int $OPERATION_UPDATE = 2;
    public int $OPERATION_DELETE = 3;
    public int $OPERATION_CREATE = 4;
    public int $OPERATION_LIST = 5;
    
    public int $ROLE_CLIENT = 1;    
    public int $ROLE_ADMIN = 100;   
    
    public function isGranted(string $user_id, string $role, string $ressource_id, int $operation=1): bool {
        $roleInt = (int)$role;
        
        if ($roleInt >= $this->ROLE_ADMIN) {
            return true;
        }
        
        if ($ressource_id !== '' && $user_id !== $ressource_id) {
            throw new Exception("Erreur autorisation: Vous n'avez pas accès à cette ressource");
        }
        
        if (($operation === $this->OPERATION_DELETE || $operation === $this->OPERATION_UPDATE) && $roleInt < $this->ROLE_ADMIN) {
            if ($ressource_id !== '' && $user_id !== $ressource_id) {
                throw new Exception("Erreur autorisation: Droits insuffisants pour cette opération");
            }
        }
        
        return true;
    }
}