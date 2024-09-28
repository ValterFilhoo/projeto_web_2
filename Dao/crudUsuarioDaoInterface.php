?<?php

interface CrudUsuarioDao {

    public function createUsuario($usuario);

    public function readUsuario($idUsuario);

    public function readUsuarios();

    public function updateUsuario($idUsuario, $usuario);

    public function deleteUsuario($idUsuario);


}

?>