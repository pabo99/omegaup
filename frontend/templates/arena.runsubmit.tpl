			<form id="submit" method="POST">
				<button class="close">&times;</button>
				<div class="language">
					{#wordsLanguage#}
					<select name="language">
						<option value="" default="default"></option>
						<option value="cpp11">C++11</option>
						<option value="cpp">C++</option>
						<option value="c">C</option>
						<option value="cs">C#</option>
						<option value="hs">Haskell</option>
						<option value="java">Java</option>
						<option value="pas">Pascal</option>
						<option value="py">Python</option>
						<option value="rb">Ruby</option>
						<option value="lua">Lua</option>
						<option value="kp">Karel (Pascal)</option>
						<option value="kj">Karel (Java)</option>
						<option value="cat">{#wordsJustOutput#}</option>
					</select>
				</div>
				<div>{#arenaRunSubmitFilename#} <tt>Main<span class="submit-filename-extension"></span></tt></div>
				<label for="editor">{#arenaRunSubmitPaste#}</label>
				<textarea id="editor" name="code"></textarea><br/>
				<div class="file"><label>{#arenaRunSubmitUpload#} <input type="file" /></label></div><br/>
				<input type="submit" />
			</form>
