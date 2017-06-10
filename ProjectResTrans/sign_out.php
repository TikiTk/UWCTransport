<?php
        setcookie('user_id', '', time()-86400, '/');
        setcookie('sign_out', 'a', time()+86400, '/');
        header('Location: https://uwctransport-bdube83.c9.io/ProjectResTrans/index.html');
?>