package biz.prolike.hereiam.utils;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

import biz.prolike.hereiam.R;
import biz.prolike.hereiam.activities.RegisterActivity;

public class NotReciver extends BroadcastReceiver {
    private Context mContext;

    SharedPreferences prefs = null;

    String g;
    @Override
    public void onReceive(Context context, Intent intents) {
        mContext = context;
        prefs = mContext.getSharedPreferences("biz.prolike.hereiam", mContext.MODE_PRIVATE);
        if (prefs.getBoolean("child", true)) {

        } else {
            String notify = prefs.getString("notification","yes");
            if(notify.equals("yes")) {
                //Intent dailyUpdater = new Intent(context, SendApi.class);
                Intent intent = new Intent(mContext, RegisterActivity.class);
                PendingIntent pIntent = PendingIntent.getActivity(mContext, 0, intent, 0);

                // Build notification
                // Actions are just fake
                Notification noti = new Notification.Builder(mContext)
                        .setContentTitle("Don't forget to check your child")
                        .setContentText("Your child has a new calls and messages").setSmallIcon(R.mipmap.ic_launcher)
                        .setContentIntent(pIntent)
                        .build();
                NotificationManager notificationManager = (NotificationManager) mContext.getSystemService(mContext.NOTIFICATION_SERVICE);
                // hide the notification after its selected
                noti.flags |= Notification.FLAG_AUTO_CANCEL;

                notificationManager.notify(0, noti);
            }
        }
    }

}
