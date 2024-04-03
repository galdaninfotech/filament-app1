@props(['numbers'])
<div class="slider px-4" x-data="{start: true, end: false}" x-init="$refs.slider.scrollLeft = $refs.slider.scrollWidth - $refs.slider.offsetWidth">
    <div class="slider__nav">
        <button class="slider__nav__button" x-on:click="$refs.slider.scrollBy({left: $refs.slider.offsetWidth * -1, behavior: 'smooth'});" x-bind:class="start ? '' : 'slider__nav__button--active'">Prev</button>
        <button class="slider__nav__button" x-on:click="$refs.slider.scrollBy({left: $refs.slider.offsetWidth, behavior: 'smooth'});" x-bind:class="end ? '' : 'slider__nav__button--active'">Next</button>
    </div>
    <div id="slider__content" class="slider__content" x-ref="slider" x-on:scroll.debounce="$refs.slider.scrollLeft == 0 ? start = true : start = false;">
        @foreach($numbers as $key => $number)
            <div class="slider__item number @if($loop->last) animate__animated animate__bounceIn new-number @endif bg-gray-800">
                {{ $number }}
            </div>
        @endforeach
    </div>
</div>


<style>
:root {
  --scrollcolor: #1f2937;
  --scrollbackground: #dbdbdb;
}

* {
  box-sizing: border-box;
}

/* /* body { */
  background: #203239;
} */

.slider {
  width: 90%;
  max-width: 1280px;
  margin: 5px auto;
}

.slider__content {
  overflow-x: scroll;
  -ms-scroll-snap-type: x mandatory;
      scroll-snap-type: x mandatory;
  display: flex;
  align-items: center;
  gap: 0.15rem;
  padding-bottom: 0.5rem;
  //scrollbar-color: var(--scrollcolor) var(--scrollbackground);
}
.slider__content::-webkit-scrollbar {
  height: 0.2rem;
  width: 0.2rem;
  background: var(--scrollbackground);
}
.slider__content::-webkit-scrollbar-thumb {
  background: var(--scrollcolor);
}
.slider__content::-webkit-scrollbar-track {
  background: var(--scrollbackground);
  display: flex;
}

.slider__item {
  scroll-snap-align: start;
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 30px;
  width: 30px;
  height: 30px;
  background: #ddd;
  border-radius: 0.15rem;
  overflow: hidden;
  position: relative;
  aspect-ratio: 12/6;
  animation-duration: 1s;
}
@keyframes bounceIn {
    0% {
        /* transform: scale(0.1); */
        opacity: 0.7;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.animate__bounceIn {
    background: rgb(1, 48, 48);
    color: white;
    animation-name: bounceIn;
    animation-duration: 3000ms; /* Adjust duration as needed */
    animation-iteration-count: infinite;
}

.slider__nav {
  margin: 0.6rem 0 0.6rem;
  width: 100%;
  padding: 0;
  display: flex;
  gap: 0.5rem;
  align-content: center;
  align-items: center;
  justify-content: flex-end;
  pointer-events: none;
}

.slider__nav__button {
  margin: 0;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  border: 0;
  border-radius: 2rem;
  background: #ddd;
  color: #203239;
  padding: 0.4rem 0.8rem;
  font-size: 0.6rem;
  line-height: 1;
  pointer-events: none;
  transition: 0.2s ease-out;
  opacity: 0.45;
}
.slider__nav__button--active {
  opacity: 1;
  pointer-events: auto;
  cursor: pointer;
}

</style>
