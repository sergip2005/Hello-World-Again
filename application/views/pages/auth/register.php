<div class='mainInfo'>

	<h1>�������� ������ ������������</h1>

	<p>������� ���������� � ���� ����:</p>

	<?php echo form_open("register"); ?>
	<table>

		<tr>
			<td class="label">Email:</td>
			<td class="value"><?php echo form_input($email);?></td>
		</tr>

		<tr>
			<td class="label">������ (�� <?php echo $this->config->item('min_password_length', 'ion_auth') ?> �� <?php echo $this->config->item('max_password_length', 'ion_auth') ?> ��������):</td>
			<td class="value"><?php echo form_input($password);?></td>
		</tr>

		<tr>
			<td class="label">������������� ������:</td>
			<td class="value"><?php echo form_input($password_confirm);?></td>
		</tr>

		<tr>
			<td colspan="2"><?php echo form_submit('submit', '������������������');?></td>
		</tr>

	</table>
	<?php echo form_close();?>

</div>
