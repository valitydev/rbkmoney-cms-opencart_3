<?php
class ControllerExtensionPaymentRbkmoney extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/rbkmoney');

		$this->document->setTitle($this->language->get('heading_title'));
		$data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_rbkmoney', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['shop_id'])) {
			$data['error_shop_id'] = $this->error['shop_id'];
		} else {
			$data['error_shop_id'] = '';
		}

		if (isset($this->error['merchant_private_key'])) {
			$data['error_merchant_private_key'] = $this->error['merchant_private_key'];
		} else {
			$data['error_merchant_private_key'] = '';
		}

		if (isset($this->error['rbkmoney_public_key'])) {
			$data['error_rbkmoney_public_key'] = $this->error['rbkmoney_public_key'];
		} else {
			$data['error_rbkmoney_public_key'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/rbkmoney', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/rbkmoney', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_rbkmoney_shop_id'])) {
			$data['payment_rbkmoney_shop_id'] = $this->request->post['payment_rbkmoney_shop_id'];
		} else {
			$data['payment_rbkmoney_shop_id'] = $this->config->get('payment_rbkmoney_shop_id');
		}

		if (isset($this->request->post['payment_rbkmoney_merchant_private_key'])) {
			$data['payment_rbkmoney_merchant_private_key'] = $this->request->post['payment_rbkmoney_merchant_private_key'];
		} else {
			$data['payment_rbkmoney_merchant_private_key'] = $this->config->get('payment_rbkmoney_merchant_private_key');
		}

		if (isset($this->request->post['payment_rbkmoney_rbkmoney_public_key'])) {
			$data['payment_rbkmoney_rbkmoney_public_key'] = $this->request->post['payment_rbkmoney_rbkmoney_public_key'];
		} else {
			$data['payment_rbkmoney_rbkmoney_public_key'] = $this->config->get('payment_rbkmoney_rbkmoney_public_key');
		}


		if (isset($this->request->post['payment_rbkmoney_order_status_id'])) {
			$data['payment_rbkmoney_order_status_id'] = $this->request->post['payment_rbkmoney_order_status_id'];
		} elseif ($this->config->has('payment_rbkmoney_order_status_id')) {
			$data['payment_rbkmoney_order_status_id'] = $this->config->get('payment_rbkmoney_order_status_id');
		} else {
			$data['payment_rbkmoney_order_status_id'] = '5';
		}


		if (isset($this->request->post['payment_rbkmoney_order_status_progress_id'])) {
			$data['payment_rbkmoney_order_status_progress_id'] = $this->request->post['payment_rbkmoney_order_status_progress_id'];
		} elseif ($this->config->has('payment_rbkmoney_order_status_progress_id')) {
			$data['payment_rbkmoney_order_status_progress_id'] = $this->config->get('payment_rbkmoney_order_status_progress_id');
		} else {
			$data['payment_rbkmoney_order_status_progress_id'] = '2';
		}


		if (isset($this->request->post['payment_rbkmoney_order_status_cancelled_id'])) {
			$data['payment_rbkmoney_order_status_cancelled_id'] = $this->request->post['payment_rbkmoney_order_status_cancelled_id'];
		} elseif ($this->config->has('payment_rbkmoney_order_status_cancelled_id')) {
			$data['payment_rbkmoney_order_status_cancelled_id'] = $this->config->get('payment_rbkmoney_order_status_cancelled_id');
		} else {
			$data['payment_rbkmoney_order_status_cancelled_id'] = '7';
		}

		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();



		if (isset($this->request->post['payment_rbkmoney_form_company_name'])) {
			$data['payment_rbkmoney_form_company_name'] = $this->request->post['payment_rbkmoney_form_company_name'];
		} else {
			$data['payment_rbkmoney_form_company_name'] = $this->config->get('payment_rbkmoney_form_company_name');
		}

		if (isset($this->request->post['payment_rbkmoney_form_button_label'])) {
			$data['payment_rbkmoney_form_button_label'] = $this->request->post['payment_rbkmoney_form_button_label'];
		} else {
			$data['payment_rbkmoney_form_button_label'] = $this->config->get('payment_rbkmoney_form_button_label');
		}

		if (isset($this->request->post['payment_rbkmoney_form_description'])) {
			$data['payment_rbkmoney_form_description'] = $this->request->post['payment_rbkmoney_form_description'];
		} else {
			$data['payment_rbkmoney_form_description'] = $this->config->get('payment_rbkmoney_form_description');
		}

		if (isset($this->request->post['payment_rbkmoney_geo_zone_id'])) {
			$data['payment_rbkmoney_geo_zone_id'] = $this->request->post['payment_rbkmoney_geo_zone_id'];
		} else {
			$data['payment_rbkmoney_geo_zone_id'] = $this->config->get('payment_rbkmoney_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		

		if (isset($this->request->post['payment_rbkmoney_status'])) {
			$data['payment_rbkmoney_status'] = $this->request->post['payment_rbkmoney_status'];
		} else {
			$data['payment_rbkmoney_status'] = $this->config->get('payment_rbkmoney_status');
		}

		if (isset($this->request->post['payment_rbkmoney_sort_order'])) {
			$data['payment_rbkmoney_sort_order'] = $this->request->post['payment_rbkmoney_sort_order'];
		} else {
			$data['payment_rbkmoney_sort_order'] = $this->config->get('payment_rbkmoney_sort_order');
		}

		$data['notify_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/rbkmoney/callback';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/rbkmoney', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/rbkmoney')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_rbkmoney_shop_id']) {
			$this->error['shop_id'] = $this->language->get('error_shop_id');
		}

		if (!$this->request->post['payment_rbkmoney_merchant_private_key']) {
			$this->error['merchant_private_key'] = $this->language->get('error_merchant_private_key');
		}

		if (!$this->request->post['payment_rbkmoney_rbkmoney_public_key']) {
			$this->error['rbkmoney_public_key'] = $this->language->get('error_rbkmoney_public_key');
		}

		return !$this->error;
	}
}