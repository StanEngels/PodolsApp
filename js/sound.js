//test
window.onload = function () {
    "use strict";
    var paths = document.getElementsByTagName('path');
    var visualizer = document.getElementById('visualizer');
    var mask = visualizer.getElementById('mask');
    var h = document.getElementById('welcome');
    var delayDiv = document.getElementById('delay');
    var reverbDiv = document.getElementById('reverb');
    var destortionDiv = document.getElementById('distortion');

    var path;
    var report = 0;

    function bufferSound(myArrayBuffer) {
        for (var channel = 0; channel < myArrayBuffer.numberOfChannels; channel++) {
            var nowBuffering = myArrayBuffer.getChannelData(channel);
            for (var i = 0; i < myArrayBuffer.length; i++) {
                nowBuffering[i] = Math.random();
            }
        }
    }

    var soundAllowed = function (stream) {
        window.persistAudioStream = stream;
        h.innerHTML = "Have Fun!";
        h.setAttribute('style', 'opacity: 0;');

        var audioContent = new AudioContext();
        var audioStream = audioContent.createMediaStreamSource(stream);
        var analyser = audioContent.createAnalyser();
        var delay = audioContent.createDelay(1.0);
        var reverb = audioContent.createConvolver();
        var gainNode = audioContent.createGain();
        var distortion = audioContent.createWaveShaper();
        var compressor = audioContent.createDynamicsCompressor();

        function makeDistortionCurve(amount) {
            var k = typeof amount === 'number' ? amount : 50,
                n_samples = 44100,
                curve = new Float32Array(n_samples),
                deg = Math.PI / 180,
                i = 0,
                x;
            for ( ; i < n_samples; ++i ) {
                x = i * 2 / n_samples - 1;
                curve[i] = ( 3 + k ) * x * 20 * deg / ( Math.PI + k * Math.abs(x) );
            }
            return curve;
        }

        var myArrayBuffer = audioContent.createBuffer(2, audioContent.sampleRate*0.7, audioContent.sampleRate);

        bufferSound(myArrayBuffer);

        //settings

        //reverb
        reverb.buffer = myArrayBuffer;

        //delay
        delay.delayTime.value = 0.35;

        //distortion
        distortion.curve = makeDistortionCurve(1000);
        distortion.oversample = '1x';
        //compressor
        compressor.threshold.setValueAtTime(10, audioContent.currentTime);
        compressor.knee.setValueAtTime(40, audioContent.currentTime);
        compressor.ratio.setValueAtTime(12, audioContent.currentTime);
        compressor.attack.setValueAtTime(0, audioContent.currentTime);
        compressor.release.setValueAtTime(0.25, audioContent.currentTime);

        //volume
        gainNode.gain.value = 1;


        if(destortionDiv !== null){
            gainNode.gain.value = 0.5;
            audioStream.connect(distortion);
            distortion.connect(gainNode);
        }
        else {
            audioStream.connect(gainNode);
        }
        if(delayDiv !== null) {
            gainNode.connect(delay);
        }
        if(reverbDiv !== null){
            gainNode.connect(reverb);
        }

        delay.connect(analyser);
        reverb.connect(analyser);
        gainNode.connect(analyser);

        //compressor.connect(analyser);
        analyser.fftSize = 1024;
        analyser.connect(audioContent.destination);

        var frequencyArray = new Uint8Array(analyser.frequencyBinCount);
        visualizer.setAttribute('viewBox', '0 0 255 255');

        for (var i = 0; i < 255; i++) {
            path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('stroke-dasharray', '4,1');
            mask.appendChild(path);
        }
        var doDraw = function () {
            requestAnimationFrame(doDraw);
            analyser.getByteFrequencyData(frequencyArray);
            var adjustedLength;
            for (var i = 0; i < 255; i++) {
                adjustedLength = Math.floor(frequencyArray[i]) - (Math.floor(frequencyArray[i]) % 5);
                paths[i].setAttribute('d', 'M ' + (i) + ',255 l 0,-' + adjustedLength);
            }

        }
        doDraw();
    }

    var soundNotAllowed = function (error) {
        h.innerHTML = "You must allow your microphone.";
        console.log(error);
    }
    navigator.getUserMedia({audio: true}, soundAllowed, soundNotAllowed);

};