<?php
  class Pages extends Controller {
    public function __construct(){
      $this->pageModel = $this->model('Page');
    }
    
    public function index($page = 1){
      if(!is_numeric($page) || $page < 1){
        $this->view('users/wrong');
      die();
      }
      $page = intval($page);
      $number_of_results = $this->pageModel->getNumber();
      $results_per_page = 6;
      $number_of_pages = ceil($number_of_results/$results_per_page);
      if(!($number_of_results)){
        $this->view('pages/nopost');
        die();
      }
      if($page > $number_of_pages){
            $this->view('users/wrong');
            die();
        }
        else {
          $extra = ['number_of_pages' => $number_of_pages];
          $this_page_first_result = ($page-1)*$results_per_page;
          $posts = $this->pageModel->getAllImagesPaged($this_page_first_result);
          $data=['posts' => $posts];
          $this->view('pages/index', $data,$extra);
        }
    }
    public function about(){
      $data = [
        'title' => 'About Us',
        'description' => 'A fun intuative Web-App that takes or uploads photos with stickers !'
      ];
      $this->view('pages/about', $data);
    }
  }