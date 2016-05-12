package biz.prolike.hereiam.adapters;


import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

import biz.prolike.hereiam.R;
import biz.prolike.hereiam.containers.SMSData;

/**
 * Created by admin on 5/6/15.
 */
public class SMSAdapter extends ArrayAdapter<SMSData> {

    private List<SMSData> listdata=null;
    private LayoutInflater mInflater=null;
    public SMSAdapter(Activity context, List<SMSData> calldata) {
        super(context, 0);
        this.listdata=calldata;
        mInflater = context.getLayoutInflater();
    }


    @Override
    public int getCount() {
        return listdata.size();
    }


    public View getView(int position, View convertView, ViewGroup parent) {

        final ViewHolder holder;

        if (convertView == null || convertView.getTag() == null) {
            holder = new ViewHolder();
            convertView = mInflater.inflate(R.layout.sms_item, null);

            holder.callnumber = (TextView) convertView.findViewById(R.id.number);
            holder.calldate = (TextView) convertView.findViewById(R.id.date);
            holder.callduration = (TextView) convertView.findViewById(R.id.duration);
            holder.callname = (TextView) convertView.findViewById(R.id.number2);
            holder.body = (TextView) convertView.findViewById(R.id.body);
            holder.image = (ImageView) convertView.findViewById(R.id.imageView);
            convertView.setTag(holder);
        }
        else {
            holder = (ViewHolder) convertView.getTag();
        }

        SMSData calldatalist = listdata.get(position);
        String smsnumber = calldatalist.getNumber();
        String smstype  = calldatalist.getType();
        String smsdate  = calldatalist.getDate();
        String smsbody = calldatalist.getBody();
        //int calldurations = Integer.parseInt(callduration);
        holder.callnumber.setText(smsnumber+" ");
        holder.callname.setText("");



        // holder.calltype.setText("Call Type: "+calltype);

        holder.calldate.setText(smsdate);
        holder.body.setText(smsbody);

        holder.image.setImageResource(R.drawable.message_incoming);
        if(smstype.equals("1")) {
            holder.image.setImageResource(R.drawable.message_incoming);
        }
        if(smstype.equals("2")) {
            holder.image.setImageResource(R.drawable.message_outgoing);
        }

        return convertView;
    }

    private static class ViewHolder {
        TextView callnumber;
        TextView calltype;
        TextView calldate;
        TextView callduration;
        TextView callname;
        TextView body;
        ImageView image;
    }


    private String secondsToString(int pTime) {
        return String.format("%02d:%02d", pTime / 60, pTime % 60);
    }

    public static String ConvertDate(Date date) {
        String dateForMySql = "";
        if (date == null) {
            dateForMySql = null;
        } else {
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            dateForMySql = sdf.format(date);
        }

        return dateForMySql;
    }
}