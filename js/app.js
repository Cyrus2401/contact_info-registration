    //CHECKBOX
$('#flexCheckIndeterminate').on('click', function(){
    if($(this).val() == '0'){
        $('#recipient').css('display', 'none')
        $('#file').attr('required', 'required')
        $('.fieldRequis').css('display', 'inline')
        $(this).attr('value', '1')
    }else{
        $('#recipient').css('display', 'block')
        $('#file').removeAttr('required')
        $('.fieldRequis').css('display', 'none')
        $(this).attr('value', '0')
    }
})

    //INPUT SENDER_ID

$('#sender_id').on('input', function(){

    if(isNaN($(this).val())){
        $(this).attr('maxlength', 11);
    }else{
        $(this).attr('maxlength', 13);
    }

})

    //INPUT RECIPIENT
let marker = [',', ';']

let numberOfRecipient, recipientContent;

$('#recipientInput').on('input', function(){

    recipientContent = $(this).val()

    numberOfRecipientArray = recipientContent.split(/[,;]+/)
    
    numberOfRecipient = numberOfRecipientArray.length

    //$('.nbrRecipient').text(numberOfRecipient)

    let numberOfRecipientArrayWithoutSpace = numberOfRecipientArray.filter(function(val){
        if(val == "" || val.trim().length == 0 || val == undefined || val == null){
            return false;
        }
        return true;
    })

    $('.nbrRecipient').text(numberOfRecipientArrayWithoutSpace.length)

})



   // INPUT MESSAGE 
let specialChars = ['<', '>', '@', '!', '#', '$', '%', '^', '&', '*', '(', ')', '+', '[', ']', '{', '}', '?', ':', ';', '|', '\'', '\\', '"', ',',  '.', '/, ', '~', '`', '-', '=', '&', '*', '_'];

let numberOfChars, messageContent;

$('#messageInput').on('input', function(){

    messageContent = $(this).val()
    numberOfChars = $(this).val().length

    for(let i=0; i < numberOfChars; i++){

        if(jQuery.inArray(messageContent[i], specialChars) > 0){
            numberOfChars = numberOfChars + 3;
        }
    
    }

    $('.nbrChar').text(numberOfChars)
    $('.nbrSms').text(Math.ceil(numberOfChars/160))

    if(numberOfChars == 0){
        $('.nbrSms').text("1")
    }

    $('.nbrCharInput').attr('value', $('.nbrChar').text()) 
    $('.nbrSmsInput').attr('value', $('.nbrSms').text())

})


