$(document).ready(function(){

	Pusher.logToConsole = true;

	var pusher = new Pusher('6e49ca7b930fac5e00f3', {
	  cluster: 'ap1',
	  forceTLS: true
	});

	var channel = pusher.subscribe('my-channel');

	channel.bind('my-event', function(data) {
		$notif = JSON.stringify(data.message);
	 	if($notif === "Ada yang meminjam"){
	 		$.ajax({
				url: 'http://localhost:8000/api/v1/getuser',
				type: 'GET',
				dataType: 'json',
				success: function(response){
					let notconfirmborrower = response.serve.length;
					$.each(response.serve, function(index, val) {
						 console.log(val);
						 let contentbody =  document.querySelector('.dropdown-body');
						 let contentwrapper =  document.createElement('div');
						 let contentlist = document.createElement('div');
						 let iconwrapper = document.createElement('div');
						 let icon = document.createElement('i');
						 let smallName =  document.createElement('small');
						 let smallDesc = document.createElement('small');
						 let descnotifikasi = document.querySelector('.dropdown-title-text');
						 let notification = document.querySelector('.nav-link');
						 let notificationindicator = document.createElement('span');

						 notificationindicator.className = "notification-indicator notification-indicator-primary notification-indicator-ripple"

						 descnotifikasi.innerText = `Ada ${notconfirmborrower} peminjam yang belum dikonfirmasi`;

						 contentlist.className = "dropdown-list";

						 iconwrapper.className = "icon-wrapper rounded-circle bg-inverse-warning text-warning";
						 icon.className = "mdi mdi-alert";


						 iconwrapper.appendChild(icon);

						 contentwrapper.className = 'content-wrapper';

						 smallName.className = "name";
						 smallName.innerText = val.student_id.student_name;

						 smallDesc.className = "content-text";
						 smallDesc.innerText = `Meminjam ${val.item_id.item_name} : ${val.item_ammount}`;
						 
						 contentwrapper.appendChild(smallName);
						 contentwrapper.appendChild(smallDesc);

						 contentlist.appendChild(iconwrapper);
						 contentlist.appendChild(contentwrapper);

						 contentbody.appendChild(contentlist);

						 if(response.length > 0){
							notification.appendChild(notificationindicator);
						 }else{
							notification.removeChild(notificationindicator);
						 }
					});
				}
			});

	 	}
	});

})