<?php
namespace Deployer;
require 'recipe/common.php';

serverList('servers.yml');

// Configuration

set('ssh_type', 'native');
set('ssh_multiplexing', true);

//set('repository', 'ssh://stageburda@172.16.1.1:51234/~/git/stage.git');
set('repository', 'ssh://git@104.192.143.3:22/mottasistemi/burda.git');
// git@bitbucket.org:mottasistemi/burdastyle.git
//set('shared_files', []);
//set('shared_dirs', []);
//set('writable_dirs', []);

// Servers
/*
server('staging', 'web.mottasistemi.it',51234)
    ->user('burdadmin')
    //->password('multiplehands!')
    //->identityFile('/Users/cami/chiavi/authorized_keys', '/Users/cami/chiavi/id_rsa' , 'multiplehands!')
    ->identityFile('/Users/cami/chiavi/authorized_keys', '' , 'multiplehands!')
    //->identityFile('', '/Users/cami/chiavi/id_rsa' , 'multiplehands!')
    ->set('deploy_path', '/var/www/vhosts/burdastyle.com');
    //->pty(true);
*/

// Tasks

// Add test task #########################################################
task('test', function() {
    //run('ssh -vT git@github.com');
    runLocally('ssh-add -l');
    run('ssh-add -l');
});


desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    // The user must have rights for restart service
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('sudo systemctl restart php-fpm.service');
});
after('deploy:symlink', 'php-fpm:restart');

desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
