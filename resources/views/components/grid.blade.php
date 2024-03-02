
<h1>Dynamic Grid Background</h1>
<p>caniuse support:
	<a href="https://caniuse.com/#search=custom%20properties" target="_new">custom properties</a>
		<a href="https://caniuse.com/#search=output%20element" target="_new">output element</a>
			</p>

<form oninput="result.value=range.value + 'rem'">
	<label for="range">
		Grid Size Slider
	</label>
	<input type="range" name="range" id="range" min="0.25" max="5" step="0.25" value="1.0" />
	<output name="result">1.0</output>
</form>



<script>
    document.getElementById('range').addEventListener('change', (e) => {
        document.body.style.setProperty("--grid-size", e.target.value + 'rem');
    });

</script>

<style>
:root {
  --grid-size: 1rem;
}

form, body {
  display: flex;
  flex-flow: column wrap;
  justify-content: center;
  align-items: center;
}

body {
  font-family: "PT Mono", monospace;
  text-shadow: #fff 1px 1px;
  min-height: 100vh;
  background: #fff;
  background-position: center;
  background-image: linear-gradient(0deg, rgba(0, 213, 255, 0.5) 0%, transparent 1px), linear-gradient(90deg, rgba(0, 213, 255, 0.5) 0%, transparent 1px);
  background-size: 100% 1rem;
  background-size: 100% var(--grid-size), var(--grid-size) 100%;
  transition: background-size 0.25s;
}

a {
  color: #008ca8;
  transition: all 0.25s;
}
a:focus, a:hover {
  outline: none;
  text-shadow: none;
  background: #fff;
  box-shadow: #fff 0 0 0 3px;
  text-decoration-color: #1ad9ff;
  text-decoration-color: rgba(26, 217, 255, 0.5);
  color: #005566;
}

h1 {
  font-size: 2.1304347826rem;
  margin: 0 0 0.3em;
}

p {
  margin: 0;
}

form {
  margin-top: 3rem;
}

label {
  font-size: 14px;
  text-transform: uppercase;
  color: #666;
}

output {
  color: #666;
}

input[type=range] {
  margin: 0.5rem auto;
  font-size: 1.075rem;
  width: 49ch;
}
input[type=range]:after {
  content: value;
}
</style>