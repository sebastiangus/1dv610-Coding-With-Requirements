<?php


class LayoutView {
    private $loginView;
    private $dateTimeView;

    public function __construct(\view\LoginView $v, DateTimeView $dtv)
    {
        $this->loginView = $v;
        $this->dateTimeView = $dtv;
    }

    public function render() {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderIsLoggedIn() . '
          
          <div class="container">
              ' . $this->loginView->response() . '
              
              ' . $this->dateTimeView->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn() {
    if ($this->loginView->isLoggedIn()) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }
}
