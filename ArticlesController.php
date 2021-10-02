<?php
// src/Controller/ArticlesController.php(this file copy of cakephp offically website exmaple)
namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Cookie\CookieCollection;
use DateTime;

class ArticlesController extends AppController
{
	public function __construct(){
			$data = ['expires' => new DateTime('+1 hour'),'path' => '','domain' => '','secure' => false,'http' => false,];
			$cookie = Cookie::create( 'remember_my_data', $data );
		// $this->response = $this->response->withCookie(Cookie::create('remember_my_data','1234',[
		// 		        'expires' => new DateTime('+1 day'),
		// 		        'path' => '',
		// 		        'domain' => '',
		// 		        'secure' => false,
		// 		        'http' => false,
	 //    			]
		// 		));
	}

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent
    }

    public function index()
    {

			// $data = ['expires' => new DateTime('+1 hour'),'path' => '','domain' => '','secure' => false,'http' => false,];
			// $cookie = Cookie::create( 'remember_my_data', $data );
    // $test = $this->request->getCookie($cookie);
    // var_dump($test);die();

    	//$cookie = new Cookie('name','XYZ',new DateTime('+1 hour'),'','localhost',false,true);
		//$cookies = new CookieCollection([$cookie]);
		// $valueGet = CookieCollection::get($cookies);
		//echo "<pre>";
		//print_r($cookies);
		// exit();

			
			// $data = ['expires' => new DateTime('+1 hour'),'path' => '','domain' => '','secure' => false,'http' => false,];
			// $cookie = Cookie::create( 'remember_my_data', $data );
		$rememberMe = $this->request->getCookie('remember_my_data', 0);
		print_r($rememberMe);
		exit();
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles','rememberMe'));
    }

    public function view($slug)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // Hardcoding the user_id is temporary, and will be removed later
            // when we build authentication out.
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    }

    // Add the following method.

	public function edit($slug)
	{
	    $article = $this->Articles
	        ->findBySlug($slug)
	        ->firstOrFail();

	    if ($this->request->is(['post', 'put'])) {
	        $this->Articles->patchEntity($article, $this->request->getData());
	        if ($this->Articles->save($article)) {
	            $this->Flash->success(__('Your article has been updated.'));
	            return $this->redirect(['action' => 'index']);
	        }
	        $this->Flash->error(__('Unable to update your article.'));
	    }

	    $this->set('article', $article);
	}

	public function delete($slug)
	{
	    $this->request->allowMethod(['post', 'delete']);

	    $article = $this->Articles->findBySlug($slug)->firstOrFail();
	    if ($this->Articles->delete($article)) {
	        $this->Flash->success(__('The {0} article has been deleted.', $article->title));
	        return $this->redirect(['action' => 'index']);
	    }
	}
}
