<?php namespace Controllers;

use Controllers\AbstractController;
use Services\Routes\Redirector;

class DeepDive extends AbstractController
{
	protected $model;
	protected $redirect;
	
	public function __construct()
	{
		parent::__construct();

		$this->model = new \DeepDiveRequests;
		$this->redirect = new Redirector;
	}

	public function AdminScores($vars)
	{
		$this->auth->isLogin();

		$this->securePage($vars);
		
		$this->genericPageInfo('Deep Dive Admin Scores',
			'/Fakers/DeepDiveAdminScores');

		$this->data['dives'] = $this->_BuildDiveScores(
			$this->model->GetAllDiveScores());

		$this->glaze->view('Spam/diveadmin.php',$this->data);
	}

	public function MyDeepDives()
	{
		$this->auth->isLogin();

		$this->genericPageInfo('My Deep Dive Scores', 
			'/Fakers/DeepDiveAdminScores');
		
		$this->data['form'] = $this->form
			->postHandleForm('/DeepDive/AddDive')->build();

		$this->data['dives'] = $this->_BuildDiveScores(
			$this->model->GetMyDives($this->auth->id()));

		$this->glaze->view('Spam/mydives.php', $this->data);
	}

	public function AddDive()
	{
		$this->auth->isLogin();

		$check = $this->validator->string('handle', 
			$this->requests->post()->handle)->check();
		
		$this->validator->didFail($check, '/DeepDive/MyDeepDives');

		$details = $this->dbbind->GetTwitterDetails($this->auth->id());
		
		$bio = $this->twitterbind->GetUserByScreenName($details[2], 
			$details[3], $this->requests->post()->handle);

		if ($bio['code'] == 200) {	
			$this->model->AddDive(
				$this->auth->id(),
				$bio['user']->id_str,
				$bio['user']->screen_name,
				$bio['user']->followers_count,
				time()
			);

			$this->redirect
				->messages('success',
					$this->config('messages.success.addDive'))
				->to('/DeepDive/MyDeepDives');
		}

		$this->redirect
			->messages('errors',$this->config('messages.fail.addDive'))
			->to('/DeepDive/MyDeepDives');
	}

	protected function _diveForm()
	{
		$this->data['form'] = $this->form
			->open([
					'id'=>'addDive',
					'class'=>'addDive',
					'action'=>$this->routechutney->HREF('/DeepDive/AddDive',
						$this->mod_rewrite),
					'method'=>'post'
			])
			->input('text','handle',['id'=>'handle'],'Twitter Handle','handle')
			->input('submit','save');
	}

	protected function _BuildDiveScores($scores)
	{
		if (!empty($scores))
		{
			$output = '<table>';
			$output .= '<tr><th>Screen Name</th><th>Fake</th>
				<th>Inactive</th><th>Good</th>
				<th>Checks</th><th>Followers</th></tr>';
			
			foreach ($scores as $s)
			{
				$s['checks'] = isset($s['checks']) ? $s['checks'] : 0;

				$fake = isset($s['spam']) ? 
					round(($s['spam']/$s['checks'])*100) : 0;
				$inactive = isset($s['spam']) ? 
					round(($s['potential']/$s['checks'])*100) : 0;
				$good = 100-($fake+$inactive);
				
				$output .= '<tr><td>'.$s['screen_name'].'</td>
				<td class="red"><strong>'.$fake.'%</strong></td>
				<td class="orange"><strong>'.$inactive.'%</strong></td>
				<td class="green"><strong>'.$good.'%</strong></td>
				<td>'.number_format($s['checks']).'</td>
				<td>'.number_format($s['followers']).'</td></tr>';
			}
			
			$output .= '</table>';

			return $output;
		}
		
		return false;
	}
} 