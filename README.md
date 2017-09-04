# cakephp-antiflood
## Installation
```
composer require jorisvaesen/cakephp-antiflood:"dev-master"

bin/cake plugin load --bootstrap JorisVaesen/Antiflood 
```

UsersController.php
```php
public function initialize()
{
    parent::initialize();

    $this->loadComponent('JorisVaesen/Antiflood.Antiflood', [
        'ip' => true,   // filter by IP
        'cacheConfig' => 'antiflood', // cache config used to save attampts
        'maxAttempts' => 3, // maximum attempts within cache config duration
        'salt' => true, // salt identifier to be unique for an application (true = securiy salt, string = custom salt, false = not salted)
        'log' => false, // write ip and identifier to database when maxAttempts is reached, false to disable, true to enable, callback to use a custom function
    ]);
}

public function login()
{
    if ($this->request->is('post')) {
        if (!$this->Antiflood->check($this->request->getData('email'))) {
            $this->Flash->error(__('Login blocked, too many attempts'), [
                'key' => 'auth'
            ]);
            
            return;
        }

        $user = $this->Auth->identify();
        if ($user) {
            $this->Auth->setUser($user);
            if ($this->Auth->authenticationProvider()->needsPasswordRehash()) {
                $user = $this->Users->get($user['id']);
                $user->password = $this->request->getData('password');
                $this->Users->save($user);
            }
            
            return $this->redirect($this->Auth->redirectUrl());
        } else {
            $this->Antiflood->increment($this->request->getData('email'));
            $this->Flash->error(__('Username or password is incorrect'), [
                'key' => 'auth'
            ]);
        }
    }
}
```

Migrations for saving a log when maxAttempts is reached
```
bin/cake migrations migrate -p JorisVaesen/Antiflood
```
