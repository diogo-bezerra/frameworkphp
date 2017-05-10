<?php
class DData extends DTag {
    public function __construct($name, $btimg, $showAno = false, $value = '', $size = '10em') {
    	parent::__construct('span');
    	/**
    	 * Utilize esse formato para showAno: -20:+0 (20 anos atrás:ano atual)
    	 * $name = id passado na instância
    	 * 
    	 * O CSS pode ser definido através dessas ids e classes
    	 * Container geral (span): #container$name  
    	 * Input: #$name
    	 * Botão (imagem): #imgbtDatepicker$name
    	 * Container do calendário (div): #spanDatepicker$name
    	 * Calendário: .ui-datepicker
    	 */
    	
    	$this->id = "container$name";
    	$input = new DInput($name);
    	$input->id = $name;
    	$input->name = $name;
    	$input->size = $size;
    	$input->readonly = 'true';
    	$input->value = $value;
    	
    	$bt = new DImg('data', $btimg);
    	$bt->id = 'imgbtDatepicker'.$name;
    	$bt->onclick = "setDatePicker$name();$('#spanDatepicker$name').toggle('fast');";
    	
    	$spanDatepicker = new DTag('div');
    	$spanDatepicker->id = 'spanDatepicker'.$name;
    	$spanDatepicker->hidden = 'true';
    	
    	$this->setIn($this->js($name, $showAno));
    	$this->setIn($input);
    	$this->setIn($bt);
    	$this->setIn($spanDatepicker);
    }
    
    private function style() {
    	$st = "
    		<style type='text/css'>
    			
    		</style>
    	";
    	return $st;
    }
    
    private function js($name, $showAno) {
    	$anorange = '';
    	if($showAno) {
    		$anorange = "changeYear : true,
					yearRange: '$showAno',";
    	}
    	$sc = "
    		<script type='text/javascript'>
	    		function setDatePicker$name() {
	    			$('#spanDatepicker$name').datepicker({
						dateFormat: 'dd/mm/yy',
						//minDate: addDays(today, 1),
						//maxDate: maxdate,
				        onSelect: 
				        function(dateText, inst) {
				        	var date = new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay);
				        	var d = ('0'+(date.getDate())).slice(-2);
				            var m = ('0'+(date.getMonth()+1)).slice(-2);
				            var y = date.getFullYear();
				            var dt = y + '-' + (m) + '-' + d;
				            
				            $('#spanDatepicker$name').hide('fast');
				        	$('#$name').val(dateText);
			        	},
						showOtherMonths : true,
						$anorange
					}).datepicker('setDate', '0');
				}
    		</script>
    	";
    	return $sc;
    }
}

?>
