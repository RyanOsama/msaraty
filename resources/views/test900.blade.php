<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Soft Yellow Flower</title>

<style>
body {
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    overflow: hidden;
    font-family: Arial;
    color: #fff;

    /* soft background */
    background: radial-gradient(circle at center, #1a1a1a, #000);
    animation: bgSoft 8s infinite alternate;
}

/* very soft background movement */
@keyframes bgSoft {
    0% {
        background: radial-gradient(circle at center, #1a1a1a, #000);
    }
    100% {
        background: radial-gradient(circle at center, #2a2a2a, #050505);
    }
}

/* rose container */
.rose {
    position: relative;
    width: 120px;
    height: 300px;
}

/* stem */
.stem {
    position: absolute;
    bottom: 0;
    left: 58px;
    width: 4px;
    height: 0;
    background: #2e7d32;
    animation: growStem 2s forwards;
}

@keyframes growStem {
    to { height: 200px; }
}

/* leaves */
.leaf {
    position: absolute;
    width: 40px;
    height: 20px;
    background: #388e3c;
    border-radius: 50%;
    opacity: 0;
}

.leaf.left {
    bottom: 120px;
    left: 20px;
    transform: rotate(-30deg);
    animation: showLeaf 1s forwards 1.5s;
}

.leaf.right {
    bottom: 150px;
    right: 20px;
    transform: rotate(30deg);
    animation: showLeaf 1s forwards 1.8s;
}

@keyframes showLeaf {
    to { opacity: 1; }
}

/* bud */
.bud {
    position: absolute;
    bottom: 200px;
    left: 35px;
    width: 50px;
    height: 60px;
    background: #fdd835;
    border-radius: 50% 50% 40% 40%;
    transform: scale(0.5);
    animation: growBud 1.5s forwards 2s;
}

/* petals */
.petal {
    position: absolute;
    width: 60px;
    height: 60px;
    background: #ffeb3b;
    border-radius: 50%;
    top: -10px;
    left: -5px;
    opacity: 0;
}

.petal1 { animation: open1 2s forwards 3.5s; }
.petal2 { animation: open2 2s forwards 3.8s; }
.petal3 { animation: open3 2s forwards 4.1s; }

@keyframes open1 {
    to {
        transform: translate(-20px, -10px) rotate(-20deg);
        opacity: 1;
    }
}
@keyframes open2 {
    to {
        transform: translate(20px, -10px) rotate(20deg);
        opacity: 1;
    }
}
@keyframes open3 {
    to {
        transform: translate(0, -25px);
        opacity: 1;
    }
}

/* name */
.name {
    margin-top: 30px;
    font-size: 28px;
    opacity: 0;
    transform: translateY(20px);
    animation: showName 2s forwards 5.5s;
    color: #fff59d;
}

@keyframes showName {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

</style>
</head>

<body>

<div class="rose">
    <div class="stem"></div>

    <div class="leaf left"></div>
    <div class="leaf right"></div>

    <div class="bud">
        <div class="petal petal1"></div>
        <div class="petal petal2"></div>
        <div class="petal petal3"></div>
    </div>
</div>

<div class="name"> M </div>

</body>
</html>

