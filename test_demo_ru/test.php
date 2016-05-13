<!DOCTYPE html>
  <html>
    <head>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="../css/style.css"  media="screen,projection"/>
      <script src="../js/lodash.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script src="../js/sweetalert.min.js"></script> 
      <link rel="stylesheet" type="text/css" href="../css/sweetalert.css">
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
<script async>
  var countPoint = 0;
var countAnswer = 0;
var currentPage = 1;
var data, countQuestionsInTest, countQuestionsAll, min, countPointsToOK;

function onmessage(request) {
  var data = request.data;
  countQuestionsInTest = data.countQuestionsInTest;
  countQuestionsAll = data.countQuestionsAll;
  min = data.min;
  countPointsToOK = data.countPointsToOK;
  testName = data.testName;
  document.getElementById('testName').innerHTML = testName;
  document.getElementById('count-questions').innerHTML = countQuestionsInTest;
  document.getElementById('count-points-OK').innerHTML = countPointsToOK;
  document.getElementById('count-min').innerHTML = min;
  main();
};

window.addEventListener('message', onmessage, false);



</script>
    <body>
        <!-- 
          <div class="progress progress-main">
            <div class="indeterminate"></div>
          </div>
        -->
            <section id="new-window">
              <div class="row">
                <div class="col s12 m6">
                  <div class="card blue-grey darken-1">
                    <div class="card-content white-text">
                      <span class="card-title" id="testName"></span>
                      <p id="end-title">Тест состоит из <span id="count-questions"></span> случайно выбранных из списка вопросов. 
                      Тест считается выполненным, если вы правильно ответили на <span id="count-points-OK"></span> вопросов. 
                      Если вы не прошли тест, то в следующий раз пройти его сможете на следующий день.
                      Время выполнения теста <span id="count-min"></span> минут.
                      </p>
                    </div>
                    <div class="card-action">
                      <a href="#" onclick="testBegin()" id="begin-btn">Начать тестирование</a>
                    </div>
                  </div>
                </div>
              </div>
            </section>    

    <div id="content" class="hidden">

    <div style="float:right;">
      <i  class="material-icons">query_builder</i>
      Осталось <span id="last-time"></span> минут(ы).
    </div>
        
        <div id="test-body">
<script type="text/template" id="list-template">
          <% var a = []; var temp;%>
          <%rndArray(a, countQuestionsInTestT, countQuestionsAllT);%>
        <% for(var i = 1; i <= countQuestionsInTestT; i++){ %>
            <% temp = a[i-1];%> 
                <div class="hidden" id="question_block_<%= i %>"> 
                    <p class="flow-text" >Вопрос <%= i %></p>
                    <blockquote id="question_<%= i %>" ><%=questions[temp].question%></blockquote> 

                    <%if(questions[temp].type == 'oneRight'){ %>  
                      <form action="#" id="answers-radio_<%= i %>">
                        <% for(var j = 1; j <= 5;j++){ %>
                          <%if(questions[temp][j]){%>
                            <p>
                              <input class="with-gap" name="group<%=temp%>" type="radio" id="test<%= temp %>_<%= j %>" value="<%=questions[temp][j]%>">
                              <label for="test<%= temp %>_<%= j %>" id="test<%=temp%>_<%=j%>"><%=questions[temp][j]%></label>
                            </p>
                          <%} else break;%>
                        <%}%>
                      </form>
                    <div class="btn_panel">
                      <button class="btn waves-effect waves-light" onclick="isAnswer(<%=temp%>,<%=i%>)" type="submit" name="action">OK
                        <i class="material-icons right">done</i>
                    </button>
                    <% }%>

                    <%if(questions[temp].type == 'open'){ %>
                      <form action="#" class="inp-text" id="answers-radio_<%= i %>">
                        <input class="with-gap inp-text" id="test<%= temp %>_<%= i %>" name="group<%=temp%>" type="text" >
                      </form>
                    <div class="btn_panel">
                      <button class="btn waves-effect waves-light" onclick="isAnswerOpen(<%= temp %>,<%= i %>)" type="submit" name="action">OK
                      <i class="material-icons right">done</i>
                    </button>
                    <%}%>


                    <%if(questions[temp].type == 'multiRight'){ %>  
                      <form action="#" id="answers-radio_<%= i %>">
                        <% for(var j = 1; j <= 5;j++){ %>
                          <%if(questions[temp][j]){%>
                            <p>
                              <input class="with-gap" name="group<%=temp%>" type="checkbox" id="test<%= temp %>_<%= j %>" value="<%=questions[temp][j]%>">
                              <label for="test<%= temp %>_<%= j %>" id="test<%=temp%>_<%=j%>"><%=questions[temp][j]%></label>
                            </p>
                          <%} else break;%>
                        <%}%>
                      </form>
                    <div class="btn_panel">
                      <button class="btn waves-effect waves-light" onclick="isAnswerMulti(<%=temp%>,<%=i%>)" type="submit" name="action">OK
                        <i class="material-icons right">done</i>
                    </button>
                    <% }%>


                      <button class="btn waves-effect waves-light" onclick="next(<%=i%>)" type="submit" name="action">СЛЕДУЮЩИЙ
                        <i class="material-icons right">send</i>
                      </button>
                    </div>
                </div>
        <% } %>
    <ul class="pagination">
      <!-- <li class="disabled" ><a href="#"><i class="material-icons">chevron_left</i></a></li> -->
        <% for(var i = 1; i <= countQuestionsInTestT; i++){%>
            <li class="waves-effect" id="pag_<%=i%>"><a id="pag_text_<%=i%>" href="#" onclick="getQuestion(<%=i%>)"><%=i%></a></li>
        <%}%>
        <!-- <li class="waves-effect"><a href="#"><i class="material-icons">chevron_right</i></a></li> -->
      </ul>  

</script>
    </div>

  <button class="btn waves-effect waves-light fix-down deep-orange darken-3" onclick="isDone()" type="submit" name="action">ЗАВЕРШИТЬ ТЕСТ
      <i class="material-icons right">done_all</i>
  </button>
      


<script type="text/javascript" defer>
//var a = [], temp;
var questions;
//rndArray(a, 15, 30);
function main(){
var jqxhr = $.getJSON( "questions.json", function(data) {
    //console.log(jqxhr.responseText);
  questions = JSON.parse(jqxhr.responseText);
  var genListQuestions = _.template(document.getElementById('list-template').innerHTML);
  document.getElementById('test-body').innerHTML = genListQuestions({ countQuestionsInTestT : countQuestionsInTest, countQuestionsAllT : countQuestionsAll});
})

}



//Отображение вопросов

function getQuestion(x){
  currentPage = x;
  for(var j = 1; j<=countQuestionsInTest;j++){
    var questionNone = document.getElementById('question_block_'+j);
    questionNone.setAttribute('class', 'hidden');

    var NoPagination = document.getElementById('pag_'+j);
    NoPagination.removeAttribute('class');
  }

    var questionNone = document.getElementById('question_block_15');
    questionNone.removeAttribute('class');
    questionNone.setAttribute('class', 'hidden');

  var questionBlock = document.getElementById('question_block_'+currentPage);
  questionBlock.removeAttribute('class');

  var activePagination = document.getElementById('pag_'+currentPage);
  activePagination.setAttribute('class', 'active');
}

//Проверка задания с выбором одного правильного ответа
function isAnswer(x,por){
  var answer = questions[x].answer;
  var inp = document.getElementsByName('group'+x);
  if(inp[0].checked || inp[1].checked || inp[2].checked || inp[3].checked || inp[4].checked){
    for (var i = 0; i < inp.length; i++) {
      if (inp[i].type == "radio" && inp[i].checked) { 
        if(inp[i].value == answer){
          Materialize.toast('Верно!', 2000);
          countPoint++;
          countAnswer++;

          var testNumber = document.getElementById('pag_'+por);
          testNumber.style.cssText="background-color: #90caf9;";
          var textTestNumber = document.getElementById('pag_text_'+por);
          textTestNumber.style.cssText="color: #fff;";
        } else{
          var $toastContent = $('<span style="color:red;">Неправильно!</span>');
          Materialize.toast($toastContent, 3000);
          countAnswer++;

          var testNumber = document.getElementById('pag_'+por);
          testNumber.style.cssText="background-color: #ef9a9a;";
          var textTestNumber = document.getElementById('pag_text_'+por);
          textTestNumber.style.cssText="color: #fff;";
        }
        for(var j=1; j<=5; j++){
          if(document.getElementById('test'+x+'_'+j)){
          document.getElementById('test'+x+'_'+j).disabled=true;
          } else{
            break;
          }
        }
      }
    }
  }
}

//Проверка задания с открытым вопросом
function isAnswerOpen(temp,x){
  var answer = questions[temp].answer;
  var inp = document.getElementById('test'+temp+'_'+x);
    if(inp.value == answer){
      Materialize.toast('Верно!', 2000);
      countPoint++;
      countAnswer++;

      var testNumber = document.getElementById('pag_'+x);
      testNumber.style.cssText="background-color: #90caf9;";
      var textTestNumber = document.getElementById('pag_text_'+x);
      textTestNumber.style.cssText="color: #fff;";
    } else{
      var $toastContent = $('<span style="color:red;">Неправильно!</span>');
      Materialize.toast($toastContent, 3000);
      countAnswer++;

      var testNumber = document.getElementById('pag_'+x);
      testNumber.style.cssText="background-color: #ef9a9a;";
      var textTestNumber = document.getElementById('pag_text_'+x);
      textTestNumber.style.cssText="color: #fff;";
    }
      if(document.getElementById('test'+temp+'_'+x)){
      document.getElementById('test'+temp+'_'+x).disabled=true;
      }
    }


//Проверка задания с выбором множества правильных ответов
function isAnswerMulti(x,por){
  var answer = questions[x].answer, userAnswer = '';
  var inp = document.getElementsByName('group'+x);
  if(inp[0].checked || inp[1].checked || inp[2].checked || inp[3].checked || inp[4].checked){
  	for (var i = 0; i < inp.length; i++) {
  	 	if(inp[i].type == "checkbox" && inp[i].checked){
    		userAnswer = userAnswer + inp[i].value;
    	}
  	}
	        if(userAnswer == answer){
	          Materialize.toast('Верно!', 2000);
	          countPoint++;
	          countAnswer++;

	          var testNumber = document.getElementById('pag_'+por);
	          testNumber.style.cssText="background-color: #90caf9;";
	          var textTestNumber = document.getElementById('pag_text_'+por);
	          textTestNumber.style.cssText="color: #fff;";
	        } else{
	          var $toastContent = $('<span style="color:red;">Неправильно!</span>');
	          Materialize.toast($toastContent, 3000);
	          countAnswer++;
          
	          var testNumber = document.getElementById('pag_'+por);
	          testNumber.style.cssText="background-color: #ef9a9a;";
	          var textTestNumber = document.getElementById('pag_text_'+por);
	          textTestNumber.style.cssText="color: #fff;";
	        }

        for(var j=1; j<=5; j++){
          if(document.getElementById('test'+x+'_'+j)){
          document.getElementById('test'+x+'_'+j).disabled=true;
          } else{
            break;
          }
        }
  }
}


//следующий вопрос
function next(x){
  currentPage = x++;
  for(var j = 1; j<=countQuestionsInTest;j++){
    var questionNone = document.getElementById('question_block_'+j);
    questionNone.setAttribute('class', 'hidden');

    var NoPagination = document.getElementById('pag_'+j);
    NoPagination.removeAttribute('class');
  }
  if(x <= countQuestionsInTest){
  var questionBlock = document.getElementById('question_block_'+(x));
  questionBlock.removeAttribute('class');

  var activePagination = document.getElementById('pag_'+(x));
  activePagination.setAttribute('class', 'active');
  }
}

//Начало теста
function testBegin(){
  var newWindow = document.getElementById('new-window');
  newWindow.setAttribute('class', 'hidden');

  var content = document.getElementById('content');
  content.removeAttribute('class');
  content.setAttribute('class', 'content');

  getQuestion(1);

  var timeUp = (min*60000);
  printNumbersTimeout10();
  setTimeout(done, timeUp);

  var viewTimer = document.getElementById('last-time');
  viewTimer.innerHTML = min;

}

//Проверка на конец теста
function isDone(){
  if(countAnswer < (countQuestionsInTest - 4)){
    var isDone = confirm("Вы не ответили ещё на "+ (countQuestionsInTest-countAnswer)+ " вопросов. Вы действительно хотите закончить тестирование?");
    if(isDone){
      done();
    }
  } else if(countAnswer < (countQuestionsInTest - 1) && countAnswer > (countQuestionsInTest - 5)){
    var isDone = confirm("Вы не ответили ещё на "+ (countQuestionsInTest-countAnswer)+ " вопроса. Вы действительно хотите закончить тестирование?");
    if(isDone){
      done();
    }
  }
  else if(countAnswer == (countQuestionsInTest - 1)){
    var isDone = confirm("Вам осталось ответить на один вопрос. Вы действительно хотите закончить тестирование?");
    if(isDone){
      done();
    }
  } else{
    done();
  }
}

// Конец теста
function done(){
  if(countPoint >= countPointsToOK){
    swal({title: "Поздравляю! Вы правильно ответили на "+countPoint+" вопросов",text: "",imageUrl: "../thumbs-up.jpg" });
  } else{
    swal({title: "Вы ответили на "+countPoint+" вопросов(а), попробуйте пройти тест снова",text: "",imageUrl: "../thumbs-down.png" });
  }

  var content = document.getElementById('content');
  content.removeAttribute('class');
  content.setAttribute('class', 'hidden');

  var newWindow = document.getElementById('new-window');
  newWindow.removeAttribute('class');

var head = document.getElementById('end-title');
if(countPoint >= countPointsToOK){
    head.innerHTML = "Тест выполнен! Вы ответили правильно на "+countPoint+" вопросов(а)";
  } else{
    head.innerHTML = "Вы не прошли тест! Вы ответили правильно на "+countPoint+" вопросов(а). Попробуйте повторить материал и пройти тест снова.";
  }
  var beginBtn = document.getElementById('begin-btn');
  beginBtn.removeAttribute('class');
  beginBtn.setAttribute('class', 'hidden');
  window.top.return_data(countPoint);
}

// Таймер
function printNumbersTimeout10() {
  var i = 0, m = 20;
  var timerId = setTimeout(function go() {
    console.log('осталось '+m+' минут!');
    document.getElementById('last-time').innerHTML = m;
    m--;
    if (i < m) setTimeout(go, 60000);   
  }, 60000);
}


//Генерация массива со случайной прогрессирующей последовательностью длинной в n, диапазона от 1 до range.
function rndArray(array, n, range){
  var dst = 0;
  for(var i =1; i< range; i++){
    if((Math.floor(Math.random() * (range - 1 + 1)) + 1) % (range - i) < (n - dst)){
      array[dst++] = i;
    }
  }
}

</script>    
    </div>  
      <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="../js/materialize.min.js"></script>
      <script>
        $(document).ready(function() {
          $('select').material_select();
        });
      </script>

    </body>
  </html>