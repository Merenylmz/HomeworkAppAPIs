const express = require("express");
const router = express.Router();
const Homework = require("../models/Homework");
const Subject = require("../models/Subject");
const verifyToken = require("./Common");


router.get("/", async(req, res)=>{
    try {
        const homeworks = await Homework.find().skip(req.query.offset).limit(req.query.limit);

        res.send(homeworks);
    } catch (error) {
        console.log(error);
    }
});

router.post("/", async(req, res)=>{
    try{
        const {user} = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token");}

        const newHomework = new Homework({
            title: req.body.title,
            description: req.body.description,
            userId: user._id,
            subjectId: req.body.subjectId
        });
        await newHomework.save();

        const subject = await Subject.findOne({_id: req.body.subjectId});
        subject.homeworks.push({
            homeworkId: newHomework._id,
            title: req.body.title
        });
        await subject.save();



        res.send({status: "Succesfully", newHomework});

    } catch(error){
        console.log(error);
    }
});

router.delete("/:id", async(req, res)=>{
    try {
        const user = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token");}

        const homework = await Homework.findOne({_id: req.params.id});
        const subject = await Subject.findOne({_id: homework.subjectId});

        const index = subject.homeworks.findIndex(h=>h.homeworkId == req.params.id);     
        subject.homeworks.splice(index, 1);
        await subject.save();
        
        await Homework.findOneAndDelete({_id: req.params.id});

        res.send({status: "OK", msg: "Deleted Succesfully"});
    } catch (error) {
        console.log(error);
    }
});

router.put("/edit/:id", async(req, res)=>{
    try {
        const user = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token")}

        await Homework.findOneAndUpdate({_id: req.params.id}, {
            title: req.body.title,
            description: req.body.description,
            subjectId: req.body.subjectId
        });

        res.send({status: "OK", msg: "Updated Succesfully"});
    } catch (error) {
        console.log(error);
    }
}); 





module.exports = router;