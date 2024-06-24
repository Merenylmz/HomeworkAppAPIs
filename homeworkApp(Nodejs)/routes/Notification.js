const express = require("express");
const router = express.Router();
const Notification = require("../models/Notification");
const verifyToken = require("./Common");
const Users = require("../models/Users");

router.get("/", async(req, res)=>{
    try {
        const {user} = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token")}

        const notifications = await Notification.find({toUser: user._id, isRead: false});

        res.send(notifications);
    } catch (error) {
        console.log(error);
    }
});

router.post("/", async(req, res)=>{
    try {
        const {user} = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token")}         

        const newNotification = new Notification({
            title: req.body.title,
            from: user._id,
            toUser: req.body.toUser
        });


        await newNotification.save();

        const toUser = await Users.findOne({_id: req.body.toUser});
        toUser.notifications.push({
            notificationId: newNotification._id
        });
        await toUser.save();

        res.send({msg: "Notification Sended", status: "OK", notification: newNotification.title});
    } catch (error) {
        console.log(error);
    }
});

//mesaj kime gittiyse onun idsi
router.get("/changeread", async(req, res)=>{
    try {
        const {user} = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token")} 

        const notifications = await Notification.findOne({_id: req.query.notificationId});
        notifications.isRead = true;
        await notifications.save();

        res.send(notifications);
    } catch (error) {
        console.log(error);
    } 
});


module.exports = router;