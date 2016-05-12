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
import biz.prolike.hereiam.containers.CallData;

public class CallAdapter extends ArrayAdapter<CallData>{

    private List<CallData> listdata=null;
    private LayoutInflater mInflater=null;
    public CallAdapter(Activity context, List<CallData> calldata) {
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
            convertView = mInflater.inflate(R.layout.call_item, null);

            holder.callnumber = (TextView) convertView.findViewById(R.id.number);
            holder.letter = (TextView) convertView.findViewById(R.id.letter);
            holder.callduration = (TextView) convertView.findViewById(R.id.duration);
            holder.callname = (TextView) convertView.findViewById(R.id.number2);
            holder.image = (ImageView) convertView.findViewById(R.id.imageView);
            convertView.setTag(holder);
        }
        else {
            holder = (ViewHolder) convertView.getTag();
        }

        CallData calldatalist = listdata.get(position);
        String callnumber=calldatalist.getCallnumber();
        String calltype=calldatalist.getCalltype();
        String calldate= calldatalist.getCalldatetime();
        String callname = calldatalist.getCallname();
        String callduration=calldatalist.getCallduration();
        String battery = calldatalist.getBattery();
        //int calldurations = Integer.parseInt(callduration);
        String lette = "";

        if(callname == null) {
            holder.callnumber.setText(callnumber);
            holder.callname.setText(" ");
            lette = callnumber.substring(0,1);
        } else
        {
            holder.callnumber.setText(callname+" ");
            holder.callname.setText(callnumber);
            lette = callname.substring(0,1);
        }


        // holder.calltype.setText("Call Type: "+calltype);
        holder.letter.setText(lette);
        holder.callduration.setText(callduration);
        switch (calltype)
        {
            case "OUTGOING":
                holder.image.setImageResource(R.drawable.call_outgoing);
                break;
            case "INCOMING":
                holder.image.setImageResource(R.drawable.call_incoming);
                break;
            case "MISSED":
                holder.image.setImageResource(R.drawable.call_missed);
                break;
            default:
                break;
        }

        return convertView;
    }

    private static class ViewHolder {
        TextView callnumber;
        TextView calltype;
        TextView letter;
        TextView callduration;
        TextView callname;
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