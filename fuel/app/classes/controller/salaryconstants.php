<?php
class Controller_Salaryconstants extends Controller_Template{

	public function action_index()
	{
		$data['salaryconstants'] = Model_Salaryconstant::find('all');
		$this->template->title = "Salaryconstants";
		$this->template->content = View::forge('salaryconstants/index', $data);

	}

	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('salaryconstants');

		if ( ! $data['salaryconstant'] = Model_Salaryconstant::find($id))
		{
			Session::set_flash('error', 'Could not find salaryconstant #'.$id);
			Response::redirect('salaryconstants');
		}

		$this->template->title = "Salaryconstant";
		$this->template->content = View::forge('salaryconstants/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Salaryconstant::validate('create');
			
			if ($val->run())
			{
				$salaryconstant = Model_Salaryconstant::forge(array(
					'employee_id' => Input::post('employee_id'),
					'pf_adj' => Input::post('pf_adj'),
					'basic' => Input::post('basic'),
					'hra' => Input::post('hra'),
					'lta' => Input::post('lta'),
					'pf' => Input::post('pf'),
				));

				if ($salaryconstant and $salaryconstant->save())
				{
					Session::set_flash('success', 'Added salaryconstant #'.$salaryconstant->id.'.');

					Response::redirect('salaryconstants');
				}

				else
				{
					Session::set_flash('error', 'Could not save salaryconstant.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Salaryconstants";
		$this->template->content = View::forge('salaryconstants/create');

	}

	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('salaryconstants');

		if ( ! $salaryconstant = Model_Salaryconstant::find($id))
		{
			Session::set_flash('error', 'Could not find salaryconstant #'.$id);
			Response::redirect('salaryconstants');
		}

		$val = Model_Salaryconstant::validate('edit');

		if ($val->run())
		{
			$salaryconstant->employee_id = Input::post('employee_id');
			$salaryconstant->pf_adj = Input::post('pf_adj');
			$salaryconstant->basic = Input::post('basic');
			$salaryconstant->hra = Input::post('hra');
			$salaryconstant->lta = Input::post('lta');
			$salaryconstant->pf = Input::post('pf');

			if ($salaryconstant->save())
			{
				Session::set_flash('success', 'Updated salaryconstant #' . $id);

				Response::redirect('salaryconstants');
			}

			else
			{
				Session::set_flash('error', 'Could not update salaryconstant #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$salaryconstant->employee_id = $val->validated('employee_id');
				$salaryconstant->pf_adj = $val->validated('pf_adj');
				$salaryconstant->basic = $val->validated('basic');
				$salaryconstant->hra = $val->validated('hra');
				$salaryconstant->lta = $val->validated('lta');
				$salaryconstant->pf = $val->validated('pf');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('salaryconstant', $salaryconstant, false);
		}

		$this->template->title = "Salaryconstants";
		$this->template->content = View::forge('salaryconstants/edit');

	}

	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('salaryconstants');

		if ($salaryconstant = Model_Salaryconstant::find($id))
		{
			$salaryconstant->delete();

			Session::set_flash('success', 'Deleted salaryconstant #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete salaryconstant #'.$id);
		}

		Response::redirect('salaryconstants');

	}


}
