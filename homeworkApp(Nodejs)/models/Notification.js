const mongoose = require("mongoose");
const notificationSchema = mongoose.Schema({
    title: String,
    from: {type: mongoose.Schema.Types.ObjectId, ref: "users"},
    toUser: {type: mongoose.Schema.Types.ObjectId, ref: "users"},
    isRead: {type: Boolean, default: false}
});

const Notification = mongoose.model("Notification", notificationSchema);

module.exports = Notification;