const mongoose = require("mongoose");

const homeworkSchema = mongoose.Schema({
    homeworkId: {type: mongoose.Schema.Types.ObjectId, ref: "homeworks"},
    title: {type: String, default: null}
});

const subjectSchema = mongoose.Schema({
    title: String,
    homeworks: {type: [homeworkSchema], default:[]}
});

const Subject = mongoose.model("Subjects", subjectSchema);

module.exports = Subject;
