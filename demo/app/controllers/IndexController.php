<?php

use App\Forms\LoginForm;
use App\Models\BillboxItems;
use App\Forms\ItemsForm;
use App\Models\BillboxOrder;

class IndexController extends ControllerBase
{

    /**
     * order list
     */
    public function indexAction()
    {
        $this->tag->setTitle('Demos :: Orders List');
        $this->view->orders = null;
        try {
            if ($this->session->has('IS_LOGIN'))
            {
                $identity= $this->auth->getIdentity();
                if(isset($identity['id']) && !empty($identity['id'])){
                    /** fetch all data with join queries using  */
                    $orders = BillboxItems::query()
                        ->columns('App\Models\BillboxItems.name,
                                   App\Models\BillboxItems.quantity,
                                   App\Models\BillboxItems.item_price,
                                   App\Models\BillboxItems.id as item_id,
                                   App\Models\BillboxItems.total_price,
                                   bo.id as order_id,
                                   bo.create_date,
                                   u.id as user_id,
                                   u.user_name as user_name
                                   ')
                        ->leftJoin( 'App\Models\BillboxOrder', 'App\Models\BillboxItems.order_id = bo.id', 'bo')
                        ->leftJoin('App\Models\BillboxUser', 'bo.user_id = u.id', 'u')
                        ->andWhere('u.id = :id:',['id'=>(int)$identity['id']])
                        ->execute()->setHydrateMode(\Phalcon\Mvc\Model\Resultset::HYDRATE_ARRAYS)
                        ->toArray();
                    if(count($orders) > 0){
                        $this->view->orders = $orders;
                    }
                }
            }else{
                return $this->response->redirect('/index/login');
            }
        }catch(Exception $ex){
            $this->flash->error($ex->getMessage());
        }
    }

    /**
     * Login Page View
     */
    public function loginAction()
    {
        $this->tag->setTitle('Demos :: Login');
        $form = new LoginForm();
        if ($this->session->has('IS_LOGIN')){
            return $this->response->redirect('/index');
         }
        try {
            if ($this->request->isPost()) {
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->auth->check([
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                    ]);

                    return $this->response->redirect('/index');
                }
            }
        } catch (Exception $e) {
            $this->flash->error($e->getMessage());
        }
        $this->view->form = $form;
    }

    /**
     * User Logout
     */
    public function logoutAction()
    {
        // Destroy the whole session
        $this->auth->remove();
        $this->session->destroy();
        return $this->response->redirect('index/login');
    }

    /**
     * create and edit order action
     * @note One order with multipe items feture not implemented 
     */
    public function editAction()
    {
        $this->tag->setTitle('Demos :: Edit Order');
       
        if (!$this->session->has('IS_LOGIN')){
            return $this->response->redirect('/index');
         }
         $identity= $this->auth->getIdentity();
        $form = new ItemsForm();
        $edit = $this->request->get("edit");
        try {
            if($edit == true){
                $orderId = $this->request->get("order_id");
                $itemId =  $this->request->get("item_id");
                $form->get('order_id')->setDefault($orderId);
                $form->get('id')->setDefault($itemId);
                //fetch value 
                if(!empty($itemId)){
                    $item = BillboxItems::findFirstById($itemId);
                    if($item){
                        $form->get('quantity')->setDefault($item->quantity);
                        $form->get('item_price')->setDefault($item->item_price);
                        $form->get('item_name')->setDefault($item->name);
                    }
                }
                $form->get('submit')->setDefault('Update');
            }

            if ($this->request->isPost()) {
                $this->db->begin();
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    $this->db->rollback();
                } else {
                    $orderId = $this->request->getPost('order_id');
                    $itemId = $this->request->getPost('id');

                    if(empty($orderId)){
                        $order = new BillboxOrder();
                    }else{
                        $order = BillboxOrder::findFirstById($orderId);
                    }
                    if(empty($itemId)){
                        $item = new BillboxItems();
                    }else{
                        $item = BillboxItems::findFirstById($itemId);
                    }
                    $totalPrice = ((float)$this->request->getPost('item_price') * (int)$this->request->getPost('quantity'));
                    $today = strtotime(date("Y-m-d H:i:s"));
                    $order->save( [
                        'user_id' =>  $identity['id'],
                        'create_date'   =>  $today,
                    ] );
                    // save item 
                    $item->save(
                        [
                            'name'       =>  $this->request->getPost('item_name'),
                            'quantity'   =>  (int)$this->request->getPost('quantity'),
                            'item_price' =>  (float)$this->request->getPost('item_price'),
                            'total_price'   => $totalPrice,
                            'order_id'   =>  $order->id,
                        ]
                    );
                    $this->db->commit();
                    $this->flash->success("Data saved succesfully.");
                }
            }
        } catch (Exception $e) {
            $this->db->rollback();
            $this->flash->error($e->getMessage());
        }
        $this->view->form = $form;
    }

}

