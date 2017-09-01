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

    $this->loadComponent('JorisVaesen/Antiflood.Antiflood');
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
