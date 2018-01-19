/*
	I wrote this code to put images in hexagons to have a way of easily scaling the images inside of them.
	This way I don't have to write a dozen classes in my SASS.
*/
var scales =
	{
		'AngularJS': 150,
		'CSS': 140,
		'Gulp': 60,
		'HTML': 140,
		'JavaScript': 150,
		'Laravel': 140,
		'NodeJS': 150,
		'PHP': 150,
		'Python': 200,
		'SASS': 100,
		'Webpack': 100
	};

(function(){
	var style = document.createElement('style');
	style.type = 'text/css';

	var fileNames = document.getElementsByClassName('hexagon');

	for(var i = 0; i < fileNames.length; i++) {
		style.innerHTML += '.imageUrl' + [i] + ':before { background-image: url(resources/images/logos/' + fileNames[i].id + '); ' +
		                   'background-color: white; background-size:' + scales[fileNames[i].title] + 'px;' +
		                   'background-position: center; ' +
		                   'background-repeat: no-repeat;}';
		document.getElementsByClassName('hexagon')[i].className += ' imageUrl' + [i];
	}
	document.getElementsByTagName('head')[0].appendChild(style);

	var containers = document.getElementsByClassName('sad');
	var marginTop = -60;
	for(var x = 0; x < containers.length; x++) {
		containers[x].setAttribute("style", "margin-bottom: 15px; margin-left: 15px; margin-top: " + marginTop + "px;");
	}
	containers[0].setAttribute("style", "margin: 50px 0 15px 360px;");
	containers[1].setAttribute("style", "margin: 50px 70px 0 15px; ");
	containers[6].setAttribute("style", "margin: -60px 0 0px 130px;");
	containers[9].setAttribute("style", "margin: -60px 0 0 475px; ");
})();

/*
	This code makes adding projects for a portfolio a lot easier.
	Instead of having it to add in the HTML, you can just add it in the array below.
*/
var projectInfo =
	[
		{
			title: 'Some title 1',
			text: 'Some description.',
			image: '../../resources/images/projects/some-image1.png',
			skills: 'Some skills 1'
		},
		{
			title: 'Some title 2',
			text: 'Some description 2.',
			image: '../../resources/images/projects/some-image2.png',
			skills: 'Some skills 2'
		},
		{
			title: 'Some title 3',
			text: 'Some description 3.',
			image: '../../resources/images/projects/some-image3.png',
			skills: 'Some skills 3'
		}
	];

var projects = document.getElementsByClassName('wrapper');
var current = [];
var c;
var arrows = document.getElementsByClassName('arrow');

(function(){
	if(projectInfo.length <= 4) {
		arrows[0].setAttribute("style", "color: #dbb1ed;");
		arrows[1].setAttribute("style", "color: #dbb1ed;");
	}
	else {
		arrows[0].onclick = previous;
		arrows[1].onclick = next;
	}

	var counter;
	if(projectInfo.length > 4) {
		counter = 4;
	}
	else {
		counter = projectInfo.length;
	}

	for(var x = 0; x < counter; x++) {
		projects[x].setAttribute("style", "visibility: visible;");
		if(projectInfo[x]) {
			projects[x].getElementsByTagName('img')[0].src=projectInfo[x].image;
			projects[x].getElementsByTagName('h3')[0].innerHTML=projectInfo[x].title;
			projects[x].getElementsByTagName('p')[0].innerHTML=projectInfo[x].text;
			projects[x].getElementsByTagName('p')[1].innerHTML=projectInfo[x].skills;
			current.push(x);
		}
	}
})();