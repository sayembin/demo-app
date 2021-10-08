<?php
namespace App\Library;
use Phalcon\Mvc\User\Component;
use App\Models\BillboxUser;
/**
 * Manages Authentication/Identity Management 
 */
class Auth extends Component
{
    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolean
     * @throws Exception
     */
    public function check($credentials)
    {

        // Check if the user exist
        $user = BillboxUser::findFirstByEmail($credentials['email']);
        if ($user == false) {
            throw new \Exception('Wrong email/password combination');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            throw new \Exception('Wrong email/password combination');
        }

        $this->session->set('auth-identity', [
            'id' => $user->id,
            'name' => $user->user_name,
        ]); 
        $this->session->set('IS_LOGIN', 1);
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['name'];
    }

    /**
     * Removes the user identity information from session
     * @return void
     */
    public function remove()
    {
        $this->session->remove('auth-identity');
        $this->session->remove('IS_LOGIN');
    }
}