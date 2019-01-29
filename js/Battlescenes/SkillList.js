const actionskills = [
    function hit(pow){
        console.log("敵一帯にダメージ");
        return pow*10;
    },
    function heal(hp){
        console.log("味方全体を回復");
        return hp/10;
    }
];