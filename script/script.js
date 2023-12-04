function formatDoc(cmd, value = null) {
	if (value) {
	  document.execCommand('insertHTML', false, `<${cmd}>${value}</${cmd}>`);
	} else {
	  document.execCommand(cmd);
	}
  }
  
  function addLink() {
	const url = prompt('Insert url');
	formatDoc('a', url);
  }
  
  const content = document.getElementById('content');
  const aElements = content.getElementsByTagName('a');
  
  content.addEventListener('mouseenter', function () {
	content.contentEditable = 'true';
	aElements.forEach(item => {
	  item.target = '_blank';
	});
  });
  
  content.addEventListener('mouseleave', function () {
	content.contentEditable = 'false';
  });
  
  const showCode = document.getElementById('show-code');
  let active = false;
  
  showCode.addEventListener('click', function () {
	showCode.dataset.active = !active;
	active = !active;
	content.contentEditable = !active;
	if (active) {
	  content.textContent = content.innerHTML;
	} else {
	  content.innerHTML = content.textContent;
	}
  });
  
  const filename = document.getElementById('filename');
  
  function fileHandle(value) {
	if (value === 'new') {
	  content.innerHTML = '';
	  filename.value = 'untitled';
	} else if (value === 'txt') {
	  const blob = new Blob([content.innerText], { type: 'text/plain' });
	  const url = URL.createObjectURL(blob);
	  const link = document.createElement('a');
	  link.href = url;
	  link.download = `${filename.value}.txt`;
	  link.click();
	} else if (value === 'pdf') {
	  html2pdf().from(content).save(`${filename.value}.pdf`);
	}
  }
  