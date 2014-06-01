<?php

echo <<<CONTENT
		<span class="title_form">Задать вопрос юристу</span>
		<form class="form_ask_questions">
			<input type="text" name="name" placeholder="ФИО">
			<textarea placeholder="Ваш вопрос..."></textarea>
			<button>Задать вопрос</button>
		</form>
CONTENT;
