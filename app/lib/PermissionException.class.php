<?php
class PermissonException extends Exception
{
    public function __construct ($requiredPermission)
    {
        parent::__construct ("Permission '".$requiredPermission."' was required", 403);
    }
}
?>
