<?php

class ControllerModuleCanonical extends Controller {
	private $error = array();

    public function index() {

		$this->load->language('module/canonical');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
        $this->load->model('module/canonical');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { //если мы нажали "Сохранить"  в панели, мы сохраняем текущие настройки
			$this->model_setting_setting->editSetting('canonical', $this->request->post);
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		//создание переменных из языкового файла
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['tab_title'] = $this->language->get('tab_title');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_rule_edit'] = $this->language->get('heading_rule_edit');
		$data['add_rule_button']  = $this->language->get('add_rule_button');
        $data['add_param_button']  = $this->language->get('add_param_button');
		$data['select_page_hint'] = $this->language->get('select_page_hint');
		$data['select_page_path'] = $this->language->get('select_page_path');
        $data['select_canonical_path'] = $this->language->get('select_canonical_path');
		$data['select_page_params'] = $this->language->get('select_page_params');
		$data['submit_rule_button'] = $this->language->get('submit_rule_button');
		$data['rules_header'] = $this->language->get('rules_header');
		$data['rules_remove_button'] = $this->language->get('rules_remove_button');
        $data['rules_url'] = $this->language->get('rules_url');
        $data['rules_canon_url'] = $this->language->get('rules_canon_url');
        $data['rules_params'] = $this->language->get('rules_params');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['host'] = 'http://' . $_SERVER['HTTP_HOST'] . '/';

        // если метод validate вернул warning, передадим его представлению
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        // формирование массива breadcrumbs (хлебные крошки)
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/canonical', 'token=' . $this->session->data['token'], 'SSL')
		);

        //ссылки для форм
		$data['action'] = $this->url->link('module/canonical', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['add_rule_href'] = $this->url->link('module/canonical/add_rule','token=' . $this->session->data['token'], 'SSL');
        $data['delete_rule_href'] = $this->url->link('module/canonical/delete_rule','token=' . $this->session->data['token'], 'SSL');

		//переменная со статусом модуля
        if (isset($this->request->post['canonical_status'])) {
			$data['canonical_status'] = $this->request->post['canonical_status'];
		} else {
			$data['canonical_status'] = $this->config->get('canonical_status');
		}

        //ссылки на контроллеры header,column_left,footer, иначе мы не сможем вывести заголовок, подвал и левое меню в файле представления
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['rules'] = $this->model_module_canonical->getRules();

        //в качестве файла представления модуля для панели администратора использовать файл canonical.tpl
		$this->response->setOutput($this->load->view('module/canonical.tpl', $data));
	}

    //обязательный метод в контроллере, он запускается для проверки разрешено ли пользователю изменять настройки данного модуля
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/canonical')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

    public function add_rule() {
        $this->load->model('module/canonical');
        $this->model_module_canonical->addRule($_REQUEST);
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }

    public function delete_rule() {
        $this->load->model('module/canonical');
        $this->model_module_canonical->deleteRule($_REQUEST['id']);
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }
}