<?php
namespace charlymatloc\api\dto;

class AuthDTO {
    public string $id;
    public string $email;
    public string $nom;
    public string $prenom;
    public string $role;
    public string $access_token;
    public string $refresh_token;

    public function __construct(string $id, string $email, string $role, string $nom,string $prenom,string $access_token, string $refresh_token) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->role = $role;
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }
}
