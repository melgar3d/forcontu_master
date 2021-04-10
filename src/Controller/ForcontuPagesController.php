<?php

/**
 * @file
 * Contains \Drupal\forcontu_pages\Controller\ForcontuPagesController.
 */

namespace Drupal\forcontu_pages\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\user\UserInterface;
use Drupal\node\NodeInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Datetime;

class ForcontuPagesController extends ControllerBase {

    protected $currentUser;
    protected $dateFormatter;



    public function __construct(AccountInterface $current_user) {
        $this->currentUser = $current_user;
    }

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('current_user')
        );
    }

    public function simple() {
        return [
            '#markup' => '<p>' . $this->t('This is a simple page (with no arguments)') . '</p>',
        ];
    }

    public function tab1() {

        $output = '<p>' . $this->t('this is the content of Tab 1') . '</p>';

        if($this->currentUser->hasPermission('administer nodes')) {
          $output .= '<p>' . $this->t('This extra text is only displayed if the current user can administer nodes') . '</p>';
        }
        return [
            '#markup' => $output,
        ];

    }

    public function tab2() {
        return [
            '#markup' => '<p>' . $this->t('this is the content of Tab 2') . '</p>',
        ];
    }

    public function tab3() {
        $output = '<p>' . $this->t('this is the content of Tab 3') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'short') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'medium') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'long') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'html_datetime') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'html_date') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'html_time') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'html_yearless_date') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'html_week') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'html_month') . '</p>'
                   . '<p>' . \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'html_month') . '</p>';
        
        return [
            '#markup' => $output,
        ];
    }

    public function tab3a() {
        return [
            '#markup' => '<p>' . $this->t('this is the content of Tab 3a') . '</p>',
        ];
    }

    public function tab3b() {
        return [
            '#markup' => '<p>' . $this->t('this is the content of Tab 3b') . '</p>',
        ];
    }

    public function extratab() {
        return [
            '#markup' => '<p>' . $this->t('this is the content of Extra Tab') . '</p>',
        ];
    }

    public function action1() {
        return [
            '#markup' => '<p>' . $this->t('this is the content of Action 1') . '</p>',
        ];
    }

    public function action2() {
        return [
            '#markup' => '<p>' . $this->t('this is the content of Action 2') . '</p>',
        ];
    }

    public function calculator($num1, $num2) {

        //a) comprobamos que los valores suministrados sean numéricos
        //y si no es así, lanzamos una excepción
        if (!is_numeric($num1) || !is_numeric($num2)) {
            throw new BadRequestHttpException(t('No numeric arguments specified.'));
        }
        
        //b) Los resultados se motrarán en formato lista HTML (ul).
        //Cada elemento de la lista se añade a un array
        $list[] = $this->t("@num1 + @num2 = @sum", 
                           ['@num1' => $num1,
                           '@num2' => $num2,
                           '@sum' => $num1 + $num2]);
        if ($num2 != 0) {
          $list[] = $this->t("@num1 / @num2 = @division",
                           ['@num1' => $num1,
                           '@num2' => $num2,
                           '@division' => $num1 / $num2]);
                        }else{
          $list[] = $this->t("@num1 / @num2 = undefined (division by zero)",
            array('@num1' => $num1, '@num2' => $num2));
        }
        
        $output['forcontu_pages_calculator'] = [
            '#theme' => 'item_list',
            '#items' => $list,
            '#title' => $this->t('Operations:'),
        ];

        return $output;
        
    }
    public function user(UserInterface $user) {
        $list[] = $this->t('Username: @username',
        ['@username' => $user->getAccountName()]);
        $list[] = $this->t('Email: @email',
        ['@email' => $user->getEmail()]);
        $list[] = $this->t('Roles: @roles',
        ['@roles' => implode(',', $user->getRoles())]);
        $list[] = $this->t('Last accessed time: @lastaccess', array('@lastaccess' => 
        \Drupal::service('date.formatter')->format($user->getLastAccessedTime(), 'short')));

        $output['forcontu_pages_user'] = [
            '#theme' => 'item_list',
            '#items' => $list,
            '#title' => $this->t('User data:'),
        ];

        return $output;
    }
    public function links() {
        // Generamos un objeto Url a partir del nombre interno de la ruta '/admin/structure/block'
        $url1 = Url::fromRoute('block.admin_display');
        $url3 = Url::fromRoute('<front>');
        $url4 = Url::fromRoute('entity.node.canonical', ['node' => 1]);
        $url5 = Url::fromRoute('entity.node.edit_form', ['node' => 1]);
        $url6 = Url::fromUri('http://www.forcontu.com');
        $url7 = Url::fromUri('internal:/core/themes/bartik/css/layout.css');
        $url8 = Url::fromUri('https://www.drupal.org');
        $url9 = Url::fromRoute('system.admin_content');
        $url10 = Url::fromRoute('entity.user.collection');

        $link_options = [
            'attributes' => [
                'class' => [
                    'external-link',
                    'list'
                ],
                'target' => '_blank',
                'title' => 'Go to drupal.org',
            ],
        ];

        $url8->setOptions($link_options);
        $url6->setOptions($link_options);

        // A partir de la Url, generamos un objeto Link con el enlace
        $link1 = Link::fromTextAndUrl(t('Go to the Block administration page'), $url1);
        $link3 = Link::fromTextAndUrl(t('Go to Front page'), $url3);
        $link4 = Link::fromTextAndUrl(t('Link to node/1'), $url4);
        $link5 = Link::fromTextAndUrl(t('Edit node/1'), $url5);
        $link6 = Link::fromTextAndUrl(t('Link to www.forcontu.com'), $url6);
        $link7 = Link::fromTextAndUrl(t('Link to layout.css'), $url7);
        $link8 = Link::fromTextAndUrl(t('Link to drupal.org'), $url8);
        $link9 = Link::fromTextAndUrl(t('Go to the Admin Content page'), $url9);
        $link10 = Link::fromTextAndUrl(t('Go to the Admin Users page'), $url10);

        $list[] = $link1;
        $list[] = $this->t('This text contains a link to %enlace. Just convert it to string to use it into a text.',
        ['%enlace' => $link1->toString()]);
        $list[] = $link3;
        $list[] = $this->t('This text conains a link to %portada.',
        ['%portada' => $link3->toString()]);
        $list[] = $link4;
        $list[] = $link5;
        $list[] = $link6;
        $list[] = $link7;
        $list[] = $link8;
        $list[] = $link9;
        $list[] = $link10;
        

        $output['forcontu_pages_user'] = [
            '#theme' => 'item_list',
            '#items' => $list,
            '#title' => $this->t('Examples of links:'),
        ];

        return $output;
    }

    public function node(NodeInterface $node){
        $list[] = $this->t('Title: @titulo', ['@titulo' => $node->getTitle()]);
        $list[] = $this->t('Type: @tipo', ['@tipo' => $node->getType()]);
        $list[] = $this->t('Created Date: @creacion', array('@creacion' =>
        \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'short')));

        $output['forcontu_pages_node'] = [
            '#theme' => 'item_list',
            '#items' => $list,
            '#title' => $this->t('Example de node:'),
        ];
        return $output;
    }
}
