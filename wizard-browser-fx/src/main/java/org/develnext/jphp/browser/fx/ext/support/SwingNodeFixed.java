package org.develnext.jphp.browser.fx.ext.support;

import javafx.event.EventType;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import sun.swing.JLightweightFrame;
 
import java.awt.*;
import java.lang.reflect.Field;
import java.security.AccessController;
import java.security.PrivilegedAction;
 
public class SwingNodeFixed extends javafx.embed.swing.SwingNode {
    private Field lwFrame;
 
    public SwingNodeFixed() {
        super();
 
        Field[] fields = this.getClass().getSuperclass().getDeclaredFields();
        for( Field field : fields ) {
            if( field.getName().equals( "lwFrame" ) ) {
                field.setAccessible( true );
                lwFrame = field;
                break;
            }
        }

        // Override key handler due to differences in JCEF's key handling
        this.setEventHandler( KeyEvent.ANY, event -> {
            JLightweightFrame frame;
            try {
                frame = (JLightweightFrame) lwFrame.get( this );
            } catch( Exception e ) {
                return;
            }
            if( frame != null ) {
                if( !event.getCharacter().isEmpty() ) {
                    if( event.getCode() == KeyCode.LEFT || event.getCode() == KeyCode.RIGHT || event.getCode() == KeyCode.UP || event.getCode() == KeyCode.DOWN || event.getCode() == KeyCode.TAB ) {
                        event.consume();
                    }
 
                    int swingID = fxKeyEventTypeToKeyID( event );
                    if( swingID >= 0 ) {
                        int swingModifiers = fxKeyModsToKeyMods( event );
                        int swingKeyCode = event.getCode().impl_getCode();
                        char swingChar = event.getCharacter().charAt( 0 );
                        if( ( (short) swingChar ) == 0 ) {
                            swingChar = (char) swingKeyCode;
                        }
                        if( event.getEventType() == KeyEvent.KEY_PRESSED ) {
                            String swingWhen = event.getText();
                            if( swingWhen.length() == 1 ) {
                                swingChar = swingWhen.charAt( 0 );
                            }
                        }
 
                        long swingWhen1 = System.currentTimeMillis();
                        java.awt.event.KeyEvent keyEvent = new java.awt.event.KeyEvent( frame, swingID, swingWhen1, swingModifiers, swingKeyCode, swingChar );
                        AccessController.doPrivileged( new PostEventAction( keyEvent ) );
                    }
                }
            }
        } );
    }
 
    private int fxKeyEventTypeToKeyID( KeyEvent event ) {
        EventType eventType = event.getEventType();
        if( eventType == KeyEvent.KEY_PRESSED ) {
            return 401;
        } else if( eventType == KeyEvent.KEY_RELEASED ) {
            return 402;
        } else if( eventType == KeyEvent.KEY_TYPED ) {
            return 400;
        } else {
            throw new RuntimeException( "Unknown KeyEvent type: " + eventType );
        }
    }
 
    private int fxKeyModsToKeyMods( KeyEvent event ) {
        int mods = 0;
        if( event.isAltDown() ) {
            mods |= 512;
        }
 
        if( event.isControlDown() ) {
            mods |= 128;
        }
 
        if( event.isMetaDown() ) {
            mods |= 256;
        }
 
        if( event.isShiftDown() ) {
            mods |= 64;
        }
 
        return mods;
    }
 
    private class PostEventAction implements PrivilegedAction< Void > {
        private AWTEvent event;
 
        public PostEventAction( AWTEvent event ) {
            this.event = event;
        }
 
        public Void run() {
            EventQueue eq = Toolkit.getDefaultToolkit().getSystemEventQueue();
            eq.postEvent( this.event );
            return null;
        }
    }
}