// Basic mocking
export const mocker = function($httpBackend) {
	$httpBackend.whenPOST('/register').respond(function() {
		return [201];
	});
};

// A basic component
import template from './register.html';

export const RegisterComponent = {
	template,
	bindings: {
		vm: '<'
	},
	controller: class RegisterComponent {
		          constructor(RegServ) {
			          this.regServ = RegServ;
			          'ngInject';
			          this.msg = {};
		          }
		          static isPasswordSame(data) {
			          return (data.cPassword === data.nPassword);
		          }

		          static isPasswordValid(data) {
			          //TODO
		          }


		          submitForm() {
			          if ( RegisterComponent.isPasswordSame(this.vm) ) {
				          if( RegisterComponent.isPasswordValid(this.vm) ) {
					          this.regServ.submitForm(this.vm)
					          .then(function() {
						          this.msg.title = "Succes";
						          this.msg.content = "You have received an e-mail to confirm your registration.";
					          }.bind(this))
					          .catch( function(error){
						          if(error.status >= 500 && error.status < 600) {
							          this.msg.title = "Error";
							          this.msg.content = "Something went wrong. Please try again later.";
						          }
						          else {
							          this.msg.title = "Error";
							          this.msg.content = "Something unexpected went wrong.";
						          }
					          }.bind(this));
				          }
				          else {
					          this.msg.title = "Wrong password";
					          this.msg.content = "Your password does not meet the requirements.";
				          }
			          }
			          else {
				          this.msg.title = "Wrong password";
				          this.msg.content = "Please make sure the passwords you entered are the same.";
			          }
			          document.getElementsByClassName('message')[0].style.display = 'block';
		          }
	          }
};


