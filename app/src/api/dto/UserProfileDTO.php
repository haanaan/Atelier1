<?php

namespace charlymatloc\api\dto;

class UserProfileDTO
{
    public string $id;
    public string $email;
    public string $role;

    public function __construct(string $id, string $email, string $role)
    {
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
    }
}
