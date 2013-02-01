function setSearchFields()
{
	var req = new Request({
		method: 'get',
		url: '<?php echo $this->ajax_url; ?>',
		data: { 'module' : 'memberlist', 'field' : $('ctrl_search').value },
		onComplete: function(response) {
			var search_ctrl = $('ctrl_search');
			search_ctrl.getAllNext().each(function(el) {
				el.dispose();
			});
			var result = JSON.decode(response);
			var relations = new Element('select', { 'id' : 'ctrl_relation', 'class' : 'select', 'name' : 'relation'});
			result['relations'].each(function(item){
					var option = new Element('option', { 'value' : item.key }).set('text', item.value);
					if ('<?php echo $this->search; ?>' == $('ctrl_search').value)
					{
						if ('<?php echo $this->relation; ?>' == item.key)
						{
							option.set('selected', 'selected');
						}
					}
					option.inject(relations);
			});
			relations.inject($('ctrl_search'), 'after');
			var ctrl_for;
			var last_element = relations;
			var label_for = new Element('label', { 'for' : 'ctrl_for', 'class' : 'invisible', 'id' : 'label_for'}).set('text', '<?php echo $this->keywords_label; ?>');
			last_element = label_for;
			label_for.inject(relations, 'after');
			if (result['field'].type == 'text')
			{
				var val = '';
				if ('<?php echo $this->search; ?>' == $('ctrl_search').value)
				{
					val = '<?php echo $this->for; ?>';
				}
				ctrl_for = new Element('input', { 'type' : 'text', 'id' : 'ctrl_for', 'value' : val, 'name' : 'for'});
				ctrl_for.inject(label_for, 'after');
				last_element = ctrl_for;
			}
			else if (result['field'].type == 'select')
			{
				ctrl_for = new Element('select', { 'id' : 'ctrl_for', 'class' : 'select', 'name' : 'for'});
				result['field'].options.each(function(item){
						var option = new Element('option', { 'value' : item.key}).set('text', item.value);
						if ('<?php echo $this->search; ?>' == $('ctrl_search').value)
						{
							if ('<?php echo $this->for; ?>' == item.key)
							{
								option.set('selected', 'selected');
							}
						}
						option.inject(ctrl_for);
				});
				ctrl_for.inject(label_for, 'after');
				last_element = ctrl_for;
			}
			var button = new Element('input', {'type' : 'submit', 'class' : 'submit', 'id' : 'button_search', 'value' : '<?php echo $this->search_label; ?>'});
			button.inject(last_element, 'after');
		}
	}).send();
}
	
$('ctrl_search').addEvent('change', function(e) {
	e.stop();
	setSearchFields();
	});
window.addEvent('domready', function() {
	setSearchFields();
});
