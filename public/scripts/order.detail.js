$(document).ready(() => {
    $('#order_status_state').change(async function () {
      const newStatus = $(this).val();
      console.log(newStatus);
  
      order_status.submit();
    });
  });
  